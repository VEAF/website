<?php

namespace App\Service\Perun;

use App\Perun\DTO\Mission;
use App\Perun\Entity\DataRaw;
use App\Perun\Entity\Instance;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class InstanceService
{
    private EntityManager $entityManager;
    private $cacheAdapter;

    public function __construct(EntityManagerInterface $entityManager, AdapterInterface $cacheAdapter)
    {
        $this->entityManager = $entityManager;
        $this->cacheAdapter = $cacheAdapter;
    }

    public function getMission(Instance $instance, bool $useCache = true): Mission
    {
        $cacheKey = sprintf('perun-instance-%d-%s', $instance->getId(), md5($instance->getMission()));

        $cacheMission = $this->cacheAdapter->getItem($cacheKey);

        if (!$cacheMission->isHit() || !$useCache) {
            $dataMission = $this->entityManager->getRepository(DataRaw::class)->findOneBy(['instance' => $instance, 'type' => DataRaw::TYPE_MISSION]);
            $mission = $this->parseMission(json_decode($dataMission->getPayload(), true));
            $mission->setInstance($instance);
            $cacheMission->set($mission);
            $this->cacheAdapter->save($cacheMission);
        }

        return $cacheMission->get();
    }

    private function parseMission($row): Mission
    {
        $row += ['mission' => []];

        return Mission::createFromJsonArray($row['mission']);
    }
}
