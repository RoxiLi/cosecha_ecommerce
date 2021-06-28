<?php

declare(strict_types=1);

namespace RebelCode\Spotlight\Instagram\SaaS;

use GuzzleHttp\ClientInterface;
use Psr\SimpleCache\CacheInterface;

class SaasResourceFetcher
{
    /** @var string */
    protected $cacheKey;

    /** @var ClientInterface|null */
    protected $client;

    /** @var CacheInterface|null */
    protected $cache;

    /**
     * Constructor.
     *
     * @param ClientInterface     $client   The HTTP client to use for sending requests.
     * @param string|null         $cacheKey The cache key where the cached responses from the server are stored.
     * @param CacheInterface|null $cache    The cache instance to use.
     */
    public function __construct(ClientInterface $client, string $cacheKey = null, CacheInterface $cache = null)
    {
        $this->client = $client;
        $this->cacheKey = $cacheKey;
        $this->cache = $cache;
    }

    public function get(): array
    {
        $fetched = false;

        if ($this->cache && $this->cache->has($this->cacheKey)) {
            $raw = $this->cache->get($this->cacheKey);
        } else {
            $response = $this->client->request('GET', '');
            $body = $response ? $response->getBody() : null;
            $raw = $body ? $body->getContents() : null;
            $fetched = true;
        }

        $decoded = json_decode($raw, true);

        if ($this->cache && $fetched && $decoded !== null) {
            $this->cache->set($this->cacheKey, $raw);
        }

        return $decoded;
    }
}
