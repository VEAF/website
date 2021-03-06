<?php

namespace App\Service;

use App\DTO\SlmodPlayerStat;
use App\DTO\SlmodVariantStat;
use App\Entity\Player;
use App\Entity\Server;
use App\Entity\Variant;
use App\Entity\VariantStat;
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
                ];

                $variant = new SlmodVariantStat();
                $variant->setVariantCode($variantCode);
                $variant->setInAir($rowTime['inAir']);
                $variant->setTotal($rowTime['total']);
                $player->addVariant($variant);
            }

            $stats[] = $player;
        }

        return $stats;
    }

    /**
     * @param SlmodPlayerStat[]|array $stats
     */
    public function importStats(Server $server, array $stats, OutputInterface $output)
    {
        $totalVariantStats = 0;

        $variantByCode = [];

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

                ++$totalVariantStats;
            }
        }

        $output->writeln(sprintf('<info>%d</info> <comment>variant stats imported</comment>', $totalVariantStats));
        $this->entityManager->flush();
    }

    public function import(Server $server, OutputInterface $output)
    {
        $stats = $this->loadStats($server);
        $this->importStats($server, $stats, $output);
    }
}
