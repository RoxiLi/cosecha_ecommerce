<?php

namespace RebelCode\Spotlight\Instagram\RestApi\EndPoints\Tools;

use RebelCode\Iris\Engine;
use RebelCode\Spotlight\Instagram\Engine\MediaItem;
use RebelCode\Spotlight\Instagram\Feeds\FeedManager;
use RebelCode\Spotlight\Instagram\RestApi\EndPoints\AbstractEndpointHandler;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Handler for the REST API endpoint that clears the cache for a specific feed.
 *
 * 0.5.3
 */
class ClearCacheFeedEndpoint extends AbstractEndpointHandler
{
    /**
     * 0.5.3
     *
     * @var Engine
     */
    protected $engine;

    /**
     * 0.5.3
     *
     * @var FeedManager
     */
    protected $feedManager;

    /**
     * Constructor.
     *
     * 0.5.3
     *
     * @param Engine      $engine
     * @param FeedManager $feedManager
     */
    public function __construct(Engine $engine, FeedManager $feedManager)
    {
        $this->engine = $engine;
        $this->feedManager = $feedManager;
    }

    /**
     * @inheritDoc
     *
     * 0.5.3
     */
    protected function handle(WP_REST_Request $request)
    {
        $options = $request->get_param('options');
        $feed = $this->feedManager->createFeed($options);

        foreach ($feed->sources as $source) {
            set_time_limit(300);

            $result = $this->engine->store->getItems($source);

            foreach ($result->items as $item) {
                wp_delete_post($item->data[MediaItem::POST], true);
            }
        }

        return new WP_REST_Response(['success' => true]);
    }
}
