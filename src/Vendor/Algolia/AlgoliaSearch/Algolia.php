<?php

namespace WebDevStudios\WPSWA\Vendor\Algolia\AlgoliaSearch;

use WebDevStudios\WPSWA\Vendor\Algolia\AlgoliaSearch\Cache\NullCacheDriver;
use WebDevStudios\WPSWA\Vendor\Algolia\AlgoliaSearch\Http\HttpClientInterface;
use WebDevStudios\WPSWA\Vendor\Algolia\AlgoliaSearch\Log\DebugLogger;
use WebDevStudios\WPSWA\Vendor\Psr\Log\LoggerInterface;
use WebDevStudios\WPSWA\Vendor\Psr\SimpleCache\CacheInterface;

final class Algolia
{
    const VERSION = '2.5.0';

    /**
     * Holds an instance of the simple cache repository (PSR-16).
     *
     * @var \WebDevStudios\WPSWA\Vendor\Psr\SimpleCache\CacheInterface|null
     */
    private static $cache;

    /**
     * Holds an instance of the logger (PSR-3).
     *
     * @var \WebDevStudios\WPSWA\Vendor\Psr\Log\LoggerInterface|null
     */
    private static $logger;

    /**
     * @var \WebDevStudios\WPSWA\Vendor\Algolia\AlgoliaSearch\Http\HttpClientInterface
     */
    private static $httpClient;

    public static function isCacheEnabled()
    {
        if (null === self::$cache) {
            return false;
        }

        return !self::getCache() instanceof NullCacheDriver;
    }

    /**
     * Gets the cache instance.
     *
     * @return \WebDevStudios\WPSWA\Vendor\Psr\SimpleCache\CacheInterface
     */
    public static function getCache()
    {
        if (null === self::$cache) {
            self::setCache(new NullCacheDriver());
        }

        return self::$cache;
    }

    /**
     * Sets the cache instance.
     */
    public static function setCache(CacheInterface $cache)
    {
        self::$cache = $cache;
    }

    /**
     * Gets the logger instance.
     *
     * @return \WebDevStudios\WPSWA\Vendor\Psr\Log\LoggerInterface
     */
    public static function getLogger()
    {
        if (null === self::$logger) {
            self::setLogger(new DebugLogger());
        }

        return self::$logger;
    }

    /**
     * Sets the logger instance.
     */
    public static function setLogger(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    public static function getHttpClient()
    {
        if (null === self::$httpClient) {
            if (class_exists('\GuzzleHttp\Client') && 6 === (int) substr(\GuzzleHttp\Client::VERSION, 0, 1)) {
                self::setHttpClient(new \WebDevStudios\WPSWA\Vendor\Algolia\AlgoliaSearch\Http\Guzzle6HttpClient());
            } else {
                self::setHttpClient(new \WebDevStudios\WPSWA\Vendor\Algolia\AlgoliaSearch\Http\Php53HttpClient());
            }
        }

        return self::$httpClient;
    }

    public static function setHttpClient(HttpClientInterface $httpClient)
    {
        self::$httpClient = $httpClient;
    }

    public static function resetHttpClient()
    {
        self::$httpClient = null;
    }
}
