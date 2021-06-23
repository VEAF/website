<?php

namespace App\Service;

use App\DTO\SlmodPlayerStat;
use App\DTO\SlmodVariantStat;
use App\DTO\SlmodWeaponStat;
use App\Entity\Player;
use App\Entity\Server;
use App\Entity\Variant;
use App\Entity\VariantStat;
use App\Entity\Weapon;
use App\Entity\WeaponStat;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Console\Output\OutputInterface;

class SlmodImportService
{
    private EntityManager $entityManager;
    private Client $apiClient;

    public function __construct(EntityManagerInterface $entityManager, Client $apiClient)
    {
        $this->entityManager = $entityManager;
        $this->apiClient = $apiClient;
    }

    /**
     * Load stats from server (ex: /slmod/test/stats).
     *
     * @return SlmodPlayerStat[]|array
     */
    public function loadStats(Server $server): array
    {
        $endpoint = sprintf('/slmod/%s/stats', $server->getCode());
        $response = $this->apiClient->get($endpoint);

        $content = $response->getBody();
        if (null === $content) {
            throw new \ErrorException(sprintf('error reading endpoint %s', $endpoint));
        }
        $data = json_decode($content, true);
        if (null === $data) {
            throw new \ErrorException(sprintf('error decoding json data from endpoint %s', $endpoint));
        }

        $stats = [];

        foreach ($data as $ucid => $row) {
            $row += [
                'id' => 0,
                'joinDate' => 0,
                'lastJoin' => 0,
                'times' => [],
            ];
            $player = new SlmodPlayerStat();
            $player->setUcid($ucid);
            $player->setJoinAt((new \DateTime())->setTimestamp($row['joinDate']));
            $player->setLastJoinAt((new \DateTime())->setTimestamp($row['lastJoin']));

            foreach ($row['times'] as $variantCode => $rowTime) {
                $rowTime += [
                    'total' => 0,
                    'inAir' => 0,
                    'weapons' => [],
                    'kills' => [],
                    'actions' => [],
                ];

                $variant = new SlmodVariantStat();
                $variant->setVariantCode($variantCode);
                $variant->setInAir($rowTime['inAir']);
                $variant->setTotal($rowTime['total']);
                $this->parseKillStats($variant, $rowTime['kills']);
                $this->parseActionsStats($variant, $rowTime['actions']);
                foreach ($rowTime['weapons'] as $weaponCode => $rowWeapon) {
                    if (!is_array($rowWeapon)) {
                        // @todo use logger
                        echo sprintf('weaponCode %s is not an array (ucid %s)', $weaponCode, $ucid) . PHP_EOL;
                    } else {
                        $variant->addWeapon($this->parseWeaponStats($weaponCode, $rowWeapon));
                    }
                }

                $player->addVariant($variant);
            }

            $stats[] = $player;
        }

        return $stats;
    }

    private function parseKillStats(SlmodVariantStat $variantStat, array $rowKills)
    {
        $rowKills += [
            'Planes' => ['total' => 0],
            'Helicopters' => ['total' => 0],
            'Ground Units' => ['total' => 0],
            'Buildings' => ['total' => 0],
            'Ships' => ['total' => 0],
        ];

        $variantStat->setKillsBuildingsTotal($rowKills['Buildings']['total']);
        $variantStat->setKillsGroundUnitsTotal($rowKills['Ground Units']['total']);
        $variantStat->setKillsPlanesTotal($rowKills['Planes']['total']);
        $variantStat->setKillsHelicoptersTotal($rowKills['Helicopters']['total']);
        $variantStat->setKillsShipsTotal($rowKills['Ships']['total']);
    }

    private function parseActionsStats(SlmodVariantStat $variantStat, array $rowActions)
    {
        $rowActions += [
            'takeoff' => [],
            'landing' => [],
            'losses' => [],
        ];

        $rowActions['losses'] += [
            'pilotDeath' => 0,
            'crash' => 0,
            'eject' => 0,
        ];

        $variantStat->setLandingTotal($this->sumLandings($rowActions['landing']));
        $variantStat->setTakeoffTotal($this->sumTakeoffs($rowActions['takeoff']));
        $variantStat->setLossesCrash($rowActions['losses']['crash']);
        $variantStat->setLossesEject($rowActions['losses']['eject']);
        $variantStat->setLossesPilotDeath($rowActions['losses']['pilotDeath']);
    }

    private function sumTakeoffs(array $rowTakeoffs): int
    {
        $total = 0;

        foreach ($rowTakeoffs as $count) {
            $total += $count;
        }

        return $total;
    }

    private function sumLandings(array $rowLandings): int
    {
        $total = 0;

        foreach ($rowLandings as $count) {
            $total += $count;
        }

        return $total;
    }

    private function parseWeaponStats(string $weaponCode, array $rowWeapon): SlmodWeaponStat
    {
        $rowWeapon += [
            'kills' => 0,
            'shot' => 0,
            'hit' => 0,
            'gun' => false,
            'numHits' => 0,
        ];

        $weapon = new SlmodWeaponStat();
        $weapon->setCode($weaponCode);
        $weapon->setGun('true' === $rowWeapon['gun'] || $rowWeapon['gun']);
        $weapon->setKills($rowWeapon['kills']);
        $weapon->setShot($rowWeapon['shot']);
        $weapon->setNumHits($rowWeapon['numHits']);

        return $weapon;
    }

    /**
     * @param SlmodPlayerStat[]|array $stats
     */
    public function importStats(Server $server, array $stats, OutputInterface $output)
    {
        $totalVariantStats = 0;
        $totalVariantWeapon = 0;

        $variantByCode = [];
        $weaponByCode = [];

        $output->writeln(sprintf('<info>%d</info> <comment>players</comment> to import', count($stats)));
        foreach ($stats as $stat) {
            $player = $this->entityManager->getRepository(Player::class)->findOneByUcid($stat->getUcid());
            if (null === $player) {
                $output->writeln(sprintf('<comment>new player</comment> <info>%s</info>', $stat->getUcid()));
                $player = new Player();
                $player->setUcid($stat->getUcid());
                $this->entityManager->persist($player);
            }
            // we keep the older join at value, without server distinction
            if (null === $player->getJoinAt() || $player->getJoinAt()->getTimestamp() < $stat->getJoinAt()->getTimestamp()) {
                $player->setJoinAt($stat->getJoinAt());
            }
            // we keep the newer last join at value, without server distinction
            if (null === $player->getLastJoinAt() || $player->getLastJoinAt()->getTimestamp() > $stat->getLastJoinAt()->getTimestamp()) {
                $player->setLastJoinAt($stat->getLastJoinAt());
            }
            foreach ($stat->getVariants() as $variantStatDTO) {
                if (!isset($variantByCode[$variantStatDTO->getVariantCode()])) {
                    $variant = $this->entityManager->getRepository(Variant::class)->findOneByCode($variantStatDTO->getVariantCode());
                    if (null === $variant) {
                        $output->writeln(sprintf('<comment>new variant</comment> <info>%s</info>', $variantStatDTO->getVariantCode()));
                        $variant = new Variant();
                        $variant->setName($variantStatDTO->getVariantCode());
                        $variant->setCode($variantStatDTO->getVariantCode());
                        $this->entityManager->persist($variant);
                    }
                    $variantByCode[$variantStatDTO->getVariantCode()] = $variant;
                } else {
                    $variant = $variantByCode[$variantStatDTO->getVariantCode()];
                }

                // find existing data
                $variantStat = $this->entityManager->getRepository(VariantStat::class)->findOneBy(['server' => $server, 'variant' => $variant, 'player' => $player]);
                if (null === $variantStat) {
                    $output->writeln(sprintf('<comment>new stat</comment> variant: <info>%s</info> player: <info>%s</info>', $variantStatDTO->getVariantCode(), $stat->getUcid()));
                    $variantStat = new VariantStat();
                    $variantStat->setServer($server);
                    $variantStat->setVariant($variant);
                    $variantStat->setPlayer($player);
                    $this->entityManager->persist($variantStat);
                }
                $variantStat->setTotal($variantStatDTO->getTotal());
                $variantStat->setInAir($variantStatDTO->getInAir());
                $variantStat->setKillsPlanesTotal($variantStatDTO->getKillsPlanesTotal());
                $variantStat->setKillsHelicoptersTotal($variantStatDTO->getKillsHelicoptersTotal());
                $variantStat->setKillsGroundUnitsTotal($variantStatDTO->getKillsGroundUnitsTotal());
                $variantStat->setKillsBuildingsTotal($variantStatDTO->getKillsBuildingsTotal());
                $variantStat->setTakeoffTotal($variantStatDTO->getTakeoffTotal());
                $variantStat->setLandingTotal($variantStatDTO->getLandingTotal());
                $variantStat->setLossesPilotDeath($variantStatDTO->getLossesPilotDeath());
                $variantStat->setLossesEject($variantStatDTO->getLossesEject());
                $variantStat->setLossesCrash($variantStatDTO->getLossesCrash());

                // parse weapons
                foreach ($variantStatDTO->getWeapons() as $weaponDTO) {
                    if (!isset($weaponByCode[$weaponDTO->getCode()])) {
                        $weapon = $this->entityManager->getRepository(Weapon::class)->findOneByCode($weaponDTO->getCode());
                        if (null === $weapon) {
                            $output->writeln(sprintf('<comment>new weapon</comment> <info>%s</info>', $weaponDTO->getCode()));
                            $weapon = new Weapon();
                            $weapon->setCode($weaponDTO->getCode());
                            $this->entityManager->persist($weapon);
                        }
                        $weaponByCode[$weaponDTO->getCode()] = $weapon;
                    } else {
                        $weapon = $weaponByCode[$weaponDTO->getCode()];
                    }

                    // find existing data (only when variant is not already new ...)
                    $weaponStat = null;
                    if (null !== $variantStat->getId()) {
                        $weaponStat = $this->entityManager->getRepository(WeaponStat::class)->findOneBy(['weapon' => $weapon, 'variantStat' => $variantStat]);
                    }
                    if (null === $weaponStat) {
                        $weaponStat = new WeaponStat();
                        $weaponStat->setWeapon($weapon);
                        $weaponStat->setVariantStat($variantStat);
                        $this->entityManager->persist($weaponStat);
                    }

                    // add / update data
                    $weaponStat->setNumHits($weaponDTO->getNumHits());
                    $weaponStat->setShot($weaponDTO->getShot());
                    $weaponStat->setGun($weaponDTO->isGun());
                    $weaponStat->setHit($weaponDTO->getHit());
                    ++$totalVariantWeapon;
                }

                ++$totalVariantStats;
            }
        }

        $output->writeln(sprintf('<info>%d</info> <comment>variant stats imported</comment>', $totalVariantStats));
        $output->writeln(sprintf('<info>%d</info> <comment>weapon stats imported</comment>', $totalVariantWeapon));
        $this->entityManager->flush();
    }

    public function import(Server $server, OutputInterface $output)
    {
        $stats = $this->loadStats($server);
        $this->importStats($server, $stats, $output);
    }
}
