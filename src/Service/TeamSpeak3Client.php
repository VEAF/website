<?php

namespace App\Service;

use App\DTO\TeamSpeakChannel;
use App\DTO\TeamSpeakClient;
use PlanetTeamSpeak\TeamSpeak3Framework\Node\Server;
use PlanetTeamSpeak\TeamSpeak3Framework\TeamSpeak3;

class TeamSpeak3Client
{
    private ?Server $client = null;
    private ?string $teamSpeakApiUrl = null;

    public function __construct(string $teamSpeakApiUrl)
    {
        $this->teamSpeakApiUrl = $teamSpeakApiUrl;
    }

    /**
     * @see https://www.php.net/manual/fr/function.parse-url.php for array format
     */
    public function getUrl(): array
    {
        return parse_url($this->teamSpeakApiUrl);
    }

    public function getClient(): ?Server
    {
        // only create connection when needed
        if (null === $this->client) {
            $this->client = TeamSpeak3::factory($this->teamSpeakApiUrl);
        }

        return $this->client;
    }

    /**
     * Disconnect from the TeamSpeak server.
     */
    public function disconnect(): void
    {
        if (null !== $this->client) {
            // Server -> Host -> ServerQuery(Adapter) -> Transport
            $this->client->getParent()->getParent()->getTransport()->disconnect();
            $this->client = null;
        }
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

            $unknowm = 'Unknown';
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
