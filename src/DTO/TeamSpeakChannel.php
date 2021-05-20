<?php

namespace App\DTO;

class TeamSpeakChannel
{
    private ?int $cid = null;
    private ?int $pid = null;
    private ?int $order = null;
    private ?string $name = null;
    private ?string $topic = null;

    private array $clients = [];

    public static function createFromNodeChannel(\TeamSpeak3_Node_Channel $nodeChannel): self
    {
        $channel = new self();

        $channel->setCid($nodeChannel['cid']);
        $channel->setPid($nodeChannel['pid']);
        // need some perimissions !
        //$channel->setOrder($nodeChannel['order']);
        $channel->setName($nodeChannel['channel_name']);
        $channel->setTopic($nodeChannel['channel_topic']);

        return $channel;
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

    public function getPid(): ?int
    {
        return $this->pid;
    }

    public function setPid(?int $pid): self
    {
        $this->pid = $pid;

        return $this;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder(?int $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(?string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function addClient(TeamSpeakClient $client)
    {
        $this->clients[] = $client;
    }

    public function getClients(): array
    {
        return $this->clients;
    }

}