<?php

namespace App\Service;

use App\DTO\TeamSpeakChannel;
use App\DTO\TeamSpeakClient;
use Symfony\Component\Cache\CacheItem;
use TeamSpeak3;

class TeamSpeak3Client
{
    private ?\TeamSpeak3_Node_Server $client = null;
    private ?string $teamSpeakApiUrl = null;

    public function __construct(string $teamSpeakApiUrl)
    {
        $this->teamSpeakApiUrl = $teamSpeakApiUrl;
    }

    public function getClient(): ?\TeamSpeak3_Node_Server
    {
        // only create connection when needed
        if (null === $this->client) {
            $this->client = TeamSpeak3::factory($this->teamSpeakApiUrl);
        }
        return $this->client;
    }

    public function countClients(): int
    {
        return count($this->getClient()->clientList()) - 1;
    }

    /**
     * @return TeamSpeakClient[]
     */
    public function getClients(): array
    {
        $clients = [];

        foreach ($this->getClient()->clientList() as $nodeClient) {
            $client = TeamSpeakClient::createFromNodeClient($nodeClient);

            $unknowm = "Unknown";
            if (substr($client->getNickName(), 0, strlen($unknowm)) == $unknowm) {
                continue;
            }

            $clients[] = $client;
        }

        return $clients;
    }

    /**
     * @return TeamSpeakChannel[]
     */
    public function getChannels(): array
    {
        $channels = [];

        foreach ($this->getClient()->channelList() as $nodeChannel) {
            $channel = TeamSpeakChannel::createFromNodeChannel($nodeChannel);

            $channels[] = $channel;
        }

        return $channels;
    }

}