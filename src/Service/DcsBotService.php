<?php

namespace App\Service;

use DcsServerBot\Api\InfoApi;
use DcsServerBot\ApiException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class DcsBotService
{
    public const CACHE_PREFIX = 'dcsbot';
    public const CACHE_EXPIRES = 60; // in seconds

    public function __construct(
        private InfoApi $infoApi,
        private AdapterInterface $cacheAdapter,
        private LoggerInterface $logger,
    ) {
    }

    private function cacheKey(string $key): string
    {
        return sprintf('%s.%s', self::CACHE_PREFIX, $key);
    }

    public function getServerStats(bool $forceRefresh = false): ?\DcsServerBot\Model\ServerStats
    {
        try {
            $cacheItem = $this->cacheAdapter->getItem($this->cacheKey('server-stats'));

            if (!$forceRefresh && $cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $serverStats = $this->infoApi->serverstatsServerapiServerstatsGet();

            $cacheItem->set($serverStats);
            $cacheItem->expiresAfter(self::CACHE_EXPIRES);
            $this->cacheAdapter->save($cacheItem);

            return $serverStats;
        } catch (ApiException $e) {
            $this->logger->error('Error fetching DcsBot server stats: '.$e->getMessage());

            return null;
        } catch (\Exception $e) {
            $this->logger->error('Error reading DcsBot server stats from cache: '.$e->getMessage());

            return null;
        }
    }

    public function getActivePlayers(bool $forceRefresh = false): ?int
    {
        $servers = $this->getServers($forceRefresh);

        if (null === $servers) {
            return null;
        }

        $count = 0;
        foreach ($servers as $server) {
            if ('Running' === $server->getStatus()) {
                $count += count($server->getPlayers() ?? []);
            }
        }

        return $count;
    }

    /**
     * @return \DcsServerBot\Model\ServerInfo[]|null
     */
    public function getServers(bool $forceRefresh = false): ?array
    {
        try {
            $cacheItem = $this->cacheAdapter->getItem($this->cacheKey('servers'));

            if (!$forceRefresh && $cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $servers = $this->infoApi->serversServerapiServersGet();

            $cacheItem->set($servers);
            $cacheItem->expiresAfter(self::CACHE_EXPIRES);
            $this->cacheAdapter->save($cacheItem);

            return $servers;
        } catch (ApiException $e) {
            $this->logger->error('Error fetching DcsBot servers: '.$e->getMessage());

            return null;
        } catch (\Exception $e) {
            $this->logger->error('Error reading DcsBot servers from cache: '.$e->getMessage());

            return null;
        }
    }
}
