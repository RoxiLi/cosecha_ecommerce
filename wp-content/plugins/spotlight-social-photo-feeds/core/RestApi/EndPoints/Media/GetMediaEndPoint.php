<?php

namespace RebelCode\Spotlight\Instagram\RestApi\EndPoints\Media;

use RebelCode\Iris\Aggregation\ItemAggregator;
use RebelCode\Iris\Aggregation\ItemFeed;
use RebelCode\Iris\Engine;
use RebelCode\Iris\Error;
use RebelCode\Iris\Source;
use RebelCode\Spotlight\Instagram\Engine\Stores\WpPostMediaStore;
use RebelCode\Spotlight\Instagram\Engine\StoryFeed;
use RebelCode\Spotlight\Instagram\Feeds\FeedManager;
use RebelCode\Spotlight\Instagram\RestApi\EndPoints\AbstractEndpointHandler;
use RebelCode\Spotlight\Instagram\Utils\Arrays;
use WP_REST_Request;
use WP_REST_Response;

/**
 * The handler for the endpoint that provides media objects.
 *
 * @since 0.1
 */
class GetMediaEndPoint extends AbstractEndpointHandler
{
    /**
     * @since 0.5
     *
     * @var Engine
     */
    protected $engine;

    /**
     * @since 0.5
     *
     * @var FeedManager
     */
    protected $feedManager;

    /**
     * Constructor.
     *
     * @since 0.1
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
     * @since 0.1
     */
    protected function handle(WP_REST_Request $request)
    {
        if ($request->has_param('source')) {
            return $this->handleBySource($request);
        }

        $options = $request->get_param('options') ?? [];
        $from = $request->get_param('from') ?? 0;
        $num = $request->get_param('num') ?? $options['numPosts']['desktop'] ?? 9;

        // Get media and total
        $feed = $this->feedManager->createFeed($options);
        $result = $this->engine->aggregate($feed, $num, $from);
        $media = ItemAggregator::getCollection($result, ItemAggregator::DEF_COLLECTION);
        $total = $result->data[ItemAggregator::DATA_TOTAL];

        // Get stories
        $storyFeed = StoryFeed::createFromFeed($feed);
        $storiesResult = $this->engine->aggregate($storyFeed);
        $stories = ItemAggregator::getCollection($storiesResult, ItemAggregator::DEF_COLLECTION);

        WpPostMediaStore::updateLastRequestedTime($result->items);

        $response = [
            'media' => $media,
            'stories' => $stories,
            'total' => $total,
            'errors' => Arrays::map($result->errors, function (Error $error) {
                return (array) $error;
            }),
        ];

        return new WP_REST_Response($response);
    }

    public function handleBySource(WP_REST_Request $request)
    {
        $srcName = $request->get_param('source');
        $srcType = $request->get_param('type');

        $from = $request->get_param('from') ?? 0;
        $num = $request->get_param('num') ?? 30;

        $source = Source::auto($srcType, ['name' => $srcName]);
        $feed = new ItemFeed([$source], [
            'postOrder' => 'date_desc',
            'mediaType' => 'all',
        ]);

        $result = $this->engine->aggregate($feed, $num, $from);
        $media = ItemAggregator::getCollection($result, ItemAggregator::DEF_COLLECTION);
        $total = $result->data[ItemAggregator::DATA_TOTAL];

        $response = [
            'media' => $media,
            'total' => $total,
            'errors' => Arrays::map($result->errors, function (Error $error) {
                return (array) $error;
            }),
        ];

        return new WP_REST_Response($response);
    }
}
