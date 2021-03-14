<?php

namespace App\Perun\Service;

use App\Perun\DTO\LogStatHourly;
use App\Perun\Entity\Instance;
use App\Perun\Entity\LogStat;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Json\Expr;
use Ob\HighchartsBundle\Highcharts\Highchart;

class LogStatService
{
    private EntityManager $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return LogStatHourly[]|array
     */
    public function loadAttendanceHourly(Instance $instance = null): array
    {
        $now = new \DateTime();

        $query = $this->entityManager->getRepository(LogStat::class)->createQueryBuilder('stat')
            ->leftJoin('stat.player', 'player')
            ->leftJoin('player.user', 'user')
            ->select('stat,player,user')
            ->andWhere('stat.datetime >= :start')
            ->setParameter('start', (new \DateTime('now'))->modify('-28 hours')); // stats are recorded at the end of the player session
        if (null !== $instance) {
            $query->join('stat.mission', 'mission')
                ->andWhere('mission.instance = :instance')
                ->setParameter('instance', $instance);
        }

        /** @var LogStat[] $entries */
        $entries = $query->getQuery()->getResult();

        // prepare all records
        $results = [];
        for ($hour = 24; $hour > 0; --$hour) {
            $start = (clone $now)->modify(sprintf('-%d hours', $hour));
            $start->setTime($start->format('G'), 0, 0);
            $end = (clone $start)->modify('+1 hour -1 second');

            $stat = new LogStatHourly();
            $stat->setHour($start->format('H:i'));
            $stat->setStart($start);
            $stat->setEnd($end);

            $hourKey = $start->format('Y-m-d H');
            $results[$hourKey] = $stat;
        }

        // remember already seen users on one same hour
        $countMap = [];

        foreach ($entries as $entry) {
            for ($hour = 0; $hour <= $entry->getTime() / 60; ++$hour) {
                $playerStartHour = (clone $entry->getDatetime())->modify(sprintf('-%d minutes +%d hours', $entry->getTime(), $hour))->format('Y-m-d H');
                if (isset($results[$playerStartHour])) {
                    /** @var LogStatHourly $stat */
                    $stat = $results[$playerStartHour];
                    // deduplicate many entries from same player in same hour
                    if (!isset($countMap[$playerStartHour][$entry->getPlayer()->getId()])) {
                        if (null !== $entry->getPlayer()->getUser()) {
                            $user = $entry->getPlayer()->getUser();
                            if ($user->isMember()) {
                                $stat->incNbMembers();
                            } elseif ($user->isCadet()) {
                                $stat->incNbCadets();
                            } else {
                                $stat->incNbGuests();
                            }
                        } else {
                            $stat->incNbUnknowns();
                        }
                        // remember counted players
                        $countMap[$playerStartHour][$entry->getPlayer()->getId()] = true;
                    }
                }
            }
        }

        return $results;
    }

    /**
     * @param LogStatHourly[]|array $stats
     */
    public function getSeriesFromLogStatHourly(array $stats)
    {
        $series = [
            0 => ['name' => 'Membres', 'color' => '#73a839', 'data' => []],
            1 => ['name' => 'Cadets', 'color' => '#2fa4e7', 'data' => []],
            2 => ['name' => 'Invités', 'color' => '#033c73', 'data' => []],
            3 => ['name' => 'Inconnus', 'color' => '#868e96', 'data' => []],
        ];

        foreach ($stats as $stat) {
            $series[0]['data'][] = $stat->getNbMembers();
            $series[1]['data'][] = $stat->getNbCadets();
            $series[2]['data'][] = $stat->getNbGuests();
            $series[3]['data'][] = $stat->getNbUnknowns();
        }

        return $series;
    }

    /**
     * @param LogStatHourly[]|array $stats
     */
    public function getCategoriesFromLogStatHourly(array $stats)
    {
        $categories = [];

        foreach ($stats as $stat) {
            $categories[] = $stat->getHour();
        }

        return $categories;
    }

    public function getAttendanceChart(string $chartName, Instance $instance = null): Highchart
    {
        $stats = $this->loadAttendanceHourly($instance);

        $history = new Highchart();
        $history->chart->renderTo($chartName);  // The #id of the div where to render the chart
        $history->chart->type('column');
        $history->credits->enabled(false);
        $history->legend->layout('vertical');
        $history->legend->align('right');
        $history->legend->verticalAlign('middle');
        if (null === $instance) {
            $history->title->text('Fréquentation des serveurs');
        } else {
            $history->title->text('Fréquentation du serveur');
        }
        $history->xAxis->title(['text' => 'Historique des 24 dernières heures']);
        $history->yAxis->title(['text' => 'Joueurs connectés']);
        $history->plotOptions->column(['stacking' => 'normal']);
        $history->xAxis->categories($this->getCategoriesFromLogStatHourly($stats));
        $history->series($this->getSeriesFromLogStatHourly($stats));

        return $history;
    }

    public function loadAttendanceHeatmapHourly(Instance $instance = null, int $weeks): array
    {
        $now = new \DateTime();
        $periodEnd = (clone $now)->modify('-1 day')->setTime(23, 59, 59);
        $periodStart = (clone $now)->modify(sprintf('-%d weeks', $weeks))->setTime(0, 0, 0);

        $query = $this->entityManager->getRepository(LogStat::class)->createQueryBuilder('stat')
            ->select('(UNIX_TIMESTAMP(stat.datetime) - 60 * stat.time) AS session_start, UNIX_TIMESTAMP(stat.datetime) AS session_end, player.id AS player_id')
            ->join('stat.player', 'player')
            ->andHaving('session_start >= :periodStart')
            ->setParameter('periodStart', $periodStart->getTimestamp())
            ->andHaving('session_end <= :periodEnd')
            ->setParameter('periodEnd', $periodEnd->getTimestamp())
            ->andWhere('stat.datetime >= :startDate')
            ->setParameter('startDate', (clone $periodStart)->modify('-4 hours')); // simple optimisation to use indexes
        if (null !== $instance) {
            $query->join('stat.mission', 'mission')
                ->andWhere('mission.instance = :instance')
                ->setParameter('instance', $instance);
        }

        $entries = $query->getQuery()->getArrayResult();

        // 2 dimensions array ? day, hour
        $results = [];

        // remember already seen users on one same hour
        $countMap = [];

        // prepare results
        for ($dayOfWeek = 0; $dayOfWeek < 7; ++$dayOfWeek) {
            $results[$dayOfWeek] = [];
            for ($hour = 0; $hour < 24; ++$hour) {
                $results[$dayOfWeek][$hour] = 0;
            }
        }

        foreach ($entries as $entry) {
            // advance with 1 hour resolution
            for ($time = $entry['session_start']; $time <= $entry['session_end']; $time += 3600) {
                $sessionKey = (new \DateTime())->setTimestamp($time)->format('Y-m-d H');
                $startDate = (new \DateTime())->setTimestamp($time);

                // deduplicate many entries from same player in same hour
                if (!isset($countMap[$sessionKey][$entry['player_id']])) {
                    $dateKey = $startDate->format('N') - 1; // day of week (0=monday - 6=sunday)
                    $hourKey = $startDate->format('G'); // hour of day
                    ++$results[$dateKey][$hourKey];

                    // remember counted players
                    $countMap[$sessionKey][$entry['player_id']] = true;
                }
            }
        }

        return $results;
    }

    public function getSeriesFromHeatmapResult(array $stats, int $divider = 1)
    {
        $series = [
            0 => [
                'name' => 'Connectés',
                'data' => [],
            ],
        ];

        foreach ($stats as $dayOfWeek => $dayStats) {
            foreach ($dayStats as $hourOfDay => $hourStats) {
                // note: inverse index of hour of day, else hours are inverted in heatmap
                $series[0]['data'][] = [$dayOfWeek, 23 - $hourOfDay, ceil($hourStats / $divider)];
            }
        }

        return $series;
    }

    public function getHeatmapChart(string $chartName, Instance $instance = null, int $weeks = 2): Highchart
    {
        $stats = $this->loadAttendanceHeatmapHourly($instance, $weeks);

        $heatmap = new Highchart();
        $heatmap->chart->renderTo($chartName);  // The #id of the div where to render the chart
        $heatmap->chart->type('heatmap');
        $heatmap->credits->enabled(false);
        $heatmap->tooltip->formatter(new Expr("function () {
        return '<b>' + getPointCategoryName(this.point, 'x') + '<b><br/>' + 
            this.point.value + '</b> joueur(s) connecté(s) à ' + getPointCategoryName(this.point, 'y') + '</b>';
        }"));
        $heatmap->legend->layout('vertical');
        $heatmap->legend->align('right');
        $heatmap->legend->verticalAlign('middle');
        $heatmap->title->text(sprintf('Fréquentation du serveur - moyenne des %d dernières semaines', $weeks));
        $heatmap->yAxis->title(['text' => '']);
        $heatmap->xAxis->categories(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']);
        $heatmap->colorAxis->min(0);
        $heatmap->colorAxis->minColor('#FFFFFF');
        $heatmap->colorAxis->maxColor(new Expr('Highcharts.getOptions().colors[0]'));

        $hours = [];
        for ($hour = 23; $hour >= 0; --$hour) {
            $hours[] = sprintf('%02d:00', $hour);
        }
        $heatmap->yAxis->categories($hours);
        $heatmap->series($this->getSeriesFromHeatmapResult($stats, $weeks));

        return $heatmap;
    }
}
