<?php

namespace App\DTO;

class TeamSpeakClient
{
    private ?int $clid = null;
    private ?int $cid = null;
    private ?int $databaseId = null;
    private ?string $nickName = null;
    private ?string $country = null;
    private ?string $uniqueIdentifier = null;
    private array $serverGroups = [];
    private ?int $channelGroupId = null;
    private ?string $version = null;
    private ?string $platform = null;
    private ?int $idleTime = null;
    private ?int $created = null;
    private ?int $lastConnected = null;

    public static function createFromNodeClient(\TeamSpeak3_Node_Client $nodeClient): self
    {
        $client = new self();

        $client->setClid($nodeClient['clid']);
        $client->setCid($nodeClient['cid']);
        $client->setDatabaseId($nodeClient['client_database_id']);
        $client->setNickName($nodeClient['client_nickname']);
        $client->setUniqueIdentifier($nodeClient['client_unique_identifier']);
        $client->setServerGroups(explode(',', $nodeClient['client_servergroups']));
        $client->setChannelGroupId($nodeClient['client_channel_group_id']);
        $client->setVersion($nodeClient['client_version']);
        $client->setPlatform($nodeClient['client_platform']);
        $client->setIdleTime($nodeClient['client_idle_time']);
        $client->setCreated($nodeClient['client_created']);
        $client->setLastConnected($nodeClient['client_created']);
        $client->setCountry($nodeClient['client_country']);

        return $client;
    }

    public function getCid(): ?int
    {
        return $this->cid;
    }

    public function setCid(?int $cid): self
    {
        $this->cid = $cid;

        return $this;
    }

    public function getClid(): ?int
    {
        return $this->clid;
    }

    public function setClid(?int $clid): self
    {
        $this->clid = $clid;

        return $this;
    }

    public function getDatabaseId(): ?int
    {
        return $this->databaseId;
    }

    public function setDatabaseId(?int $databaseId): self
    {
        $this->databaseId = $databaseId;

        return $this;
    }

    public function getNickName(): ?string
    {
        return $this->nickName;
    }

    public function setNickName(?string $nickName): self
    {
        $this->nickName = $nickName;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getChannelGroupId(): ?int
    {
        return $this->channelGroupId;
    }

    public function setChannelGroupId(?int $channelGroupId): self
    {
        $this->channelGroupId = $channelGroupId;

        return $this;
    }

    public function getCreated(): ?int
    {
        return $this->created;
    }

    public function setCreated(?int $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getIdleTime(): ?int
    {
        return $this->idleTime;
    }

    public function setIdleTime(?int $idleTime): self
    {
        $this->idleTime = $idleTime;

        return $this;
    }

    public function getLastConnected(): ?int
    {
        return $this->lastConnected;
    }

    public function setLastConnected(?int $lastConnected): self
    {
        $this->lastConnected = $lastConnected;

        return $this;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(?string $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function getServerGroups(): array
    {
        return $this->serverGroups;
    }

    public function setServerGroups(array $serverGroups): self
    {
        $this->serverGroups = $serverGroups;

        return $this;
    }

    public function getUniqueIdentifier(): ?string
    {
        return $this->uniqueIdentifier;
    }

    public function setUniqueIdentifier(?string $uniqueIdentifier): self
    {
        $this->uniqueIdentifier = $uniqueIdentifier;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): self
    {
        $this->version = $version;

        return $this;
    }
}