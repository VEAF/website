<?php

namespace App\Service;

use App\DTO\SlmodPlayerStat;
use App\DTO\SlmodVariantStat;
use App\Entity\File;
use App\Entity\Player;
use App\Entity\Server;
use App\Entity\Variant;
use App\Entity\VariantStat;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * Load stats from server (ex: /slmod/test/stats)
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
    public function importStats(Server $server, array $stats)
    {
        $variantByCode = [];

        foreach ($stats as $stat) {
            $player = $this->entityManager->getRepository(Player::class)->findOneByUcid($stat->getUcid());
            if (null === $player) {
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
                        $variant = new Variant();
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
                    $variantStat = new VariantStat();
                    $variantStat->setServer($server);
                    $variantStat->setVariant($variant);
                    $variantStat->setPlayer($player);
                    $this->entityManager->persist($variantStat);
                }
                $variantStat->setTotal($variantStatDTO->getTotal());
                $variantStat->setInAir($variantStatDTO->getInAir());
            }
        }

        $this->entityManager->flush();
    }

    public function import(Server $server)
    {
        $stats = $this->loadStats($server);
        $this->importStats($server, $stats);
    }
}
