<?php

namespace App\Perun\Service;

use App\Perun\DTO\LogStatHourly;
use App\Perun\Entity\Instance;
use App\Perun\Entity\LogStat;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
        for ($hour = 24; $hour > 0; $hour--) {
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

        $countMap = [];

        foreach ($entries as $entry) {
            for ($hour = 0; $hour <= $entry->getTime() / 60; $hour++) {
                $playerStartHour = (clone $entry->getDatetime())->modify(sprintf('-%d minutes +%d hours', $entry->getTime(), $hour))->format('Y-m-d H');
                dump($playerStartHour);
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
            0 => ['name' => 'Membres', 'color'=>'#73a839', 'data' => []],
            1 => ['name' => 'Cadets', 'color'=>'#2fa4e7', 'data' => []],
            2 => ['name' => 'Invités', 'color'=>'#033c73', 'data' => []],
            3 => ['name' => 'Inconnus', 'color'=>'#868e96', 'data' => []],
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
        $history->title->text('Fréquentation du serveur');
        $history->xAxis->title(array('text' => "Historique des 24 dernières heures"));
        $history->yAxis->title(array('text' => "Joueurs connectés"));
        $history->plotOptions->column(['stacking' => 'normal']);
        $history->chart->credits(['enabled'=> false]); // should works, but no ...
        $history->xAxis->categories($this->getCategoriesFromLogStatHourly($stats));
        $history->series($this->getSeriesFromLogStatHourly($stats));

        return $history;
    }
}