<?php

namespace App\Perun\Service;

use App\Entity\DcsBotSyncState;
use App\Perun\DTO\DcsBotStatistic;
use App\Perun\Entity\DataType;
use App\Perun\Entity\LogStat;
use App\Perun\Entity\Player;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Service for importing DCS Bot statistics into Perun database.
 */
class DcsBotImportService
{
    private const BATCH_SIZE = 100;

    private EntityManagerInterface $entityManager;
    private Connection $dcsBotConnection;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        Connection $dcsBotConnection,
        LoggerInterface $logger,
    ) {
        $this->entityManager = $entityManager;
        $this->dcsBotConnection = $dcsBotConnection;
        $this->logger = $logger;
    }

    /**
     * Imports statistics from DCS Bot database.
     */
    public function import(string $serverId, OutputInterface $output, bool $dryRun = false): int
    {
        $lastSync = $this->getLastSyncTimestamp($serverId);

        $output->writeln(sprintf(
            'Importing DCS Bot stats for server <info>%s</info> since <comment>%s</comment>',
            $serverId,
            null !== $lastSync ? $lastSync->format('Y-m-d H:i:s') : 'beginning'
        ));

        $statistics = $this->fetchStatistics($lastSync);
        $output->writeln(sprintf('Found <info>%d</info> records to import', count($statistics)));

        if (0 === count($statistics)) {
            return 0;
        }

        $imported = $this->importStatistics($statistics, $output, $dryRun);

        if (!$dryRun) {
            $this->updateSyncState($serverId, $imported);
        }

        $output->writeln(sprintf('<info>%d</info> records imported', $imported));

        return $imported;
    }

    /**
     * Fetches statistics from DCS Bot database.
     *
     * @return DcsBotStatistic[]
     */
    private function fetchStatistics(?\DateTime $since): array
    {
        $sql = 'SELECT * FROM statistics WHERE hop_off IS NOT NULL';
        $params = [];
        $types = [];

        if (null !== $since) {
            $sql .= ' AND hop_off > :since';
            $params['since'] = $since->format('Y-m-d H:i:s');
        }

        $sql .= ' ORDER BY hop_off ASC';

        $result = $this->dcsBotConnection->executeQuery($sql, $params, $types);

        $statistics = [];
        while ($row = $result->fetchAssociative()) {
            $statistics[] = DcsBotStatistic::fromRow($row);
        }

        return $statistics;
    }

    /**
     * Imports statistics into Perun database.
     *
     * @param DcsBotStatistic[] $statistics
     */
    private function importStatistics(array $statistics, OutputInterface $output, bool $dryRun): int
    {
        $playerCache = [];  // ucid => Player
        $typeCache = [];    // slot => DataType
        $imported = 0;

        foreach ($statistics as $stat) {
            // Find or create Perun Player
            $player = $this->findOrCreatePlayer($stat->getPlayerUcid(), $playerCache, $dryRun);

            // Find or create DataType
            $type = $this->findOrCreateDataType($stat->getSlot(), $typeCache, $dryRun);

            // Create LogStat
            $logStat = $this->createLogStat($stat, $player, $type);

            if (!$dryRun) {
                $this->entityManager->persist($logStat);
            }

            ++$imported;

            // Batch flush
            if (!$dryRun && 0 === $imported % self::BATCH_SIZE) {
                $this->entityManager->flush();
                $this->entityManager->clear(LogStat::class);
                $output->writeln(sprintf('  Flushed batch: %d records', $imported));
            }
        }

        // Final flush
        if (!$dryRun) {
            $this->entityManager->flush();
        }

        return $imported;
    }

    /**
     * Finds or creates a Perun Player by UCID.
     *
     * @param array<string, Player> $cache
     */
    private function findOrCreatePlayer(string $ucid, array &$cache, bool $dryRun): Player
    {
        if (isset($cache[$ucid])) {
            return $cache[$ucid];
        }

        $player = $this->entityManager->getRepository(Player::class)
            ->findOneBy(['ucid' => $ucid]);

        if (null === $player) {
            $player = new Player();
            $player->setUcid($ucid);
            $player->setUpdated(new \DateTime());

            if (!$dryRun) {
                $this->entityManager->persist($player);
            }

            $this->logger->info('Created new Perun player', ['ucid' => $ucid]);
        }

        $cache[$ucid] = $player;

        return $player;
    }

    /**
     * Finds or creates a DataType by slot name.
     *
     * @param array<string, DataType> $cache
     */
    private function findOrCreateDataType(string $slot, array &$cache, bool $dryRun): DataType
    {
        if (isset($cache[$slot])) {
            return $cache[$slot];
        }

        $type = $this->entityManager->getRepository(DataType::class)
            ->findOneBy(['name' => $slot]);

        if (null === $type) {
            $type = new DataType();
            $type->setName($slot);
            $type->setUpdated(new \DateTime());

            if (!$dryRun) {
                $this->entityManager->persist($type);
            }

            $this->logger->info('Created new DataType', ['slot' => $slot]);
        }

        $cache[$slot] = $type;

        return $type;
    }

    /**
     * Creates a LogStat entity from DCS Bot statistic.
     */
    private function createLogStat(
        DcsBotStatistic $stat,
        Player $player,
        DataType $type,
    ): LogStat {
        $logStat = new LogStat();
        $logStat->setPlayer($player);
        $logStat->setType($type);
        $logStat->setMission(null);  // No mission link as per requirements
        $logStat->setDatetime($stat->getHopOff());
        $logStat->setTime((int) ceil($stat->getSessionDuration() / 60)); // Convert seconds to minutes (rounded up)

        // Direct mappings
        $logStat->setKillsX($stat->getKills());
        $logStat->setPvp($stat->getPvp());
        $logStat->setDeaths($stat->getDeaths());
        $logStat->setEjections($stat->getEjections());
        $logStat->setCrashes($stat->getCrashes());
        $logStat->setTeamKills($stat->getTeamkills());
        $logStat->setKillsPlanes($stat->getKillsPlanes());
        $logStat->setKillsHelicopters($stat->getKillsHelicopters());
        $logStat->setKillsShips($stat->getKillsShips());

        // Field mappings with name changes
        $logStat->setKillsAirDefense($stat->getKillsSams());
        $logStat->setKillsArmor($stat->getKillsGround());

        // Aggregate takeoffs/landings to "other" category
        $logStat->setOtherTakeoffs($stat->getTakeoffs());
        $logStat->setOtherLandings($stat->getLandings());

        // Leave other fields at default 0
        $logStat->setStatus(null);

        return $logStat;
    }

    /**
     * Gets the last sync timestamp for a server.
     */
    private function getLastSyncTimestamp(string $serverId): ?\DateTime
    {
        $state = $this->entityManager->getRepository(DcsBotSyncState::class)
            ->find($serverId);

        return null !== $state ? $state->getLastSyncAt() : null;
    }

    /**
     * Updates the sync state after import.
     */
    private function updateSyncState(string $serverId, int $recordsImported): void
    {
        $state = $this->entityManager->getRepository(DcsBotSyncState::class)
            ->find($serverId);

        if (null === $state) {
            $state = new DcsBotSyncState();
            $state->setServerId($serverId);
            $this->entityManager->persist($state);
        }

        $state->setLastSyncAt(new \DateTime());
        $state->setRecordsImported($state->getRecordsImported() + $recordsImported);

        $this->entityManager->flush();
    }
}
