<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tracks the last synchronization state for DCS Bot statistics import.
 *
 * @ORM\Table(name="dcsbot_sync_state")
 *
 * @ORM\Entity
 */
class DcsBotSyncState
{
    /**
     * @ORM\Id
     *
     * @ORM\Column(type="string", length=50)
     */
    private string $serverId;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $lastSyncAt;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private int $recordsImported = 0;

    public function getServerId(): string
    {
        return $this->serverId;
    }

    public function setServerId(string $serverId): self
    {
        $this->serverId = $serverId;

        return $this;
    }

    public function getLastSyncAt(): \DateTime
    {
        return $this->lastSyncAt;
    }

    public function setLastSyncAt(\DateTime $lastSyncAt): self
    {
        $this->lastSyncAt = $lastSyncAt;

        return $this;
    }

    public function getRecordsImported(): int
    {
        return $this->recordsImported;
    }

    public function setRecordsImported(int $recordsImported): self
    {
        $this->recordsImported = $recordsImported;

        return $this;
    }
}
