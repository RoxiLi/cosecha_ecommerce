<?php

declare(strict_types=1);

namespace RebelCode\Spotlight\Instagram\Modules;

use Dhii\Services\Factories\Constructor;
use Dhii\Services\Factories\StringService;
use Dhii\Services\Factories\Value;
use Dhii\Services\Factory;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use RebelCode\Spotlight\Instagram\Feeds\Templates\FeedTemplatesProvider;
use RebelCode\Spotlight\Instagram\Module;
use wpdb;
use WpOop\TransientCache\CachePool;

class TemplatesModule extends Module
{
    public function run(ContainerInterface $c)
    {
        add_action('spotlight/instagram/rest_api/clear_cache', function () use ($c) {
            /** @var $cache CachePool */
            $cache = $c->get('cache');
            $cache->clear();
        });
    }

    public function getFactories()
    {
        return [
            // Config
            'base_url' => new StringService('{0}/templates', ['@saas/server/base_url']),
            // Client
            'client' => new Constructor(Client::class, ['client/options']),
            'client/options' => new Factory(['base_url'], function ($baseUrl) {
                return ['base_uri' => $baseUrl];
            }),
            // Cache
            'cache/key' => new Value('templates.remote'),
            'cache' => new Factory(['@wp/db',], function (wpdb $wpdb) {
                return new CachePool($wpdb, 'sli_templates', uniqid('sli_templates'), 3600);
            }),
            // Provider
            'provider' => new Constructor(FeedTemplatesProvider::class, ['client', 'cache/key', 'cache']),
        ];
    }

    public function getExtensions()
    {
        return [];
    }
}
