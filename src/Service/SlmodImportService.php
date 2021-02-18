<?php

namespace App\Service;

use App\DTO\SlmodPlayerStat;
use App\DTO\SlmodVariantStat;
use App\Entity\File;
use App\Entity\Player;
use App\Entity\Server;
use App\Entity\Variant;
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

        dump($stats);

        return $stats;
    }

    /**
     * @param SlmodPlayerStat[]|array $stats
     */
    public function importStats(array $stats)
    {
        $variantByCode = [];

        foreach ($stats as $stat) {
            $player = $this->entityManager->getRepository(Player::class)->findOneByUcid($stat->getUcid());
            if (null === $player) {
                $player = new Player();
                $player->setUcid($stat->getUcid());
                $this->entityManager->persist($player);
            }
            $player->setJoinAt($stat->getJoinAt());
            $player->setLastJoinAt($stat->getLastJoinAt());
            foreach ($stat->getVariants() as $variantStat) {
                if (!isset($variantByCode[$variantStat->getVariantCode()])) {
                    $variant = $this->entityManager->getRepository(Variant::class)->findOneByCode($variantStat->getVariantCode());
                    if (null === $variant) {
                        $variant = new Variant();
                        $variant->setCode($variantStat->getVariantCode());
                        $this->entityManager->persist($variant);
                    }
                    $variantByCode[$variantStat->getVariantCode()] = $variant;
                }
            }
        }

        $this->entityManager->flush();
    }

    public function import(Server $server)
    {
        $stats = $this->loadStats($server);
        $this->importStats($stats);
    }
}
