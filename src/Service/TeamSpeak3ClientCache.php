<?php

namespace App\Service;

use App\DTO\TeamSpeakChannel;
use App\DTO\TeamSpeakClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\ItemInterface;
use TeamSpeak3;

class TeamSpeak3ClientCache
{
    private ?TeamSpeak3Client $ts3Client = null;
    private $cacheAdapter = null;
    /** @var LoggerInterface */
    private $logger = null;

    /**
     * @var TeamSpeakClient[]
     */
    private $clients = null;

    /**
     * @var TeamSpeakChannel[]
     */
    private $channels = null;

    const CACHE_PREFIX = "teamspeak3";
    const CACHE_EXPIRES = 120; // in seconds

    public function __construct(TeamSpeak3Client $ts3Client, AdapterInterface $cacheAdapter, LoggerInterface $logger)
    {
        $this->ts3Client = $ts3Client;
        $this->cacheAdapter = $cacheAdapter;
        $this->logger = $logger;
    }

    public function getTeamSpeakClient(): TeamSpeak3Client
    {
        return $this->ts3Client;
    }

    private function cacheKey(string $key): string
    {
        return sprintf("%s.%s", static::CACHE_PREFIX, $key);
    }

    /**
     * @return TeamSpeakClient[]|null
     */
    public function getClients(): ?array
    {
        try {
            $cacheClients = $this->cacheAdapter->getItem($this->cacheKey('clients'));
            return $cacheClients->get();
        } catch (\Exception $e) {
            $this->logger->error('error reading teamspeak clients: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Save team speak clients list (and count) in cache
     */
    public function putClients(): void
    {
        $cacheClients = $this->cacheAdapter->getItem($this->cacheKey('clients'));
        try {
            // store in object as cache
            $this->clients = $this->ts3Client->getClients();
        } catch (\Exception $e) {
            $this->clients = [];
            $this->logger->error('error reading teamspeak clients: ' . $e->getMessage());
        }
        $cacheClients->set($this->clients);
        $cacheClients->expiresAfter(self::CACHE_EXPIRES);
        $this->cacheAdapter->save($cacheClients);

        $cacheClientsCount = $this->cacheAdapter->getItem($this->cacheKey('clients-count'));
        $cacheClientsCount->expiresAfter(self::CACHE_EXPIRES);
        $cacheClientsCount->set(count($cacheClients->get()));
        $this->cacheAdapter->save($cacheClientsCount);
    }

    /**
     * @return TeamSpeakChannel[]
     */
    public function getChannels(): ?array
    {
        try {
            $cacheChannels = $this->cacheAdapter->getItem($this->cacheKey('channels'));
            return $cacheChannels->get();
        } catch (\Exception $e) {
            $this->logger->error('error reading teamspeak channels: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Save team speak channels list (and clients in channels) in cache
     */
    public function putChannels(): void
    {
        $cacheChannels = $this->cacheAdapter->getItem($this->cacheKey('channels'));
        try {
            // store in object as cache
            $this->channels = $this->ts3Client->getChannels();
        } catch (\Exception $e) {
            $this->logger->error('error reading teamspeak clients: ' . $e->getMessage());
            $this->channels = [];
        }
        $cacheChannels->set($this->channels);
        $cacheChannels->expiresAfter(self::CACHE_EXPIRES);

        // only if clients are already loaded
        if (is_array($this->clients)) {
            $this->fillChannelClients($this->channels, $this->clients);
        }

        $this->cacheAdapter->save($cacheChannels);
    }

    public function countClients(): ?int
    {
        $cacheClientsCount = $this->cacheAdapter->getItem($this->cacheKey('clients-count'));

        return $cacheClientsCount->get();
    }

    /**
     * @param TeamSpeakChannel[] $channels
     * @param TeamSpeakClient[] $clients
     */
    private function fillChannelClients(array $channels, array $clients)
    {
        foreach ($channels as $channel) {
            foreach ($clients as $client) {
                if ($channel->getCid() == $client->getCid()) {
                    $channel->addClient($client);
                }
            }
        }
    }
}