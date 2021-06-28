<?php

declare(strict_types=1);

namespace RebelCode\Spotlight\Instagram;

use RebelCode\Iris\Aggregation\ItemAggregator;
use RebelCode\Iris\Engine;
use RebelCode\Iris\Error;
use RebelCode\Iris\Result;
use RebelCode\Spotlight\Instagram\Engine\Stores\WpPostMediaStore;
use RebelCode\Spotlight\Instagram\Engine\StoryFeed;
use RebelCode\Spotlight\Instagram\Feeds\FeedManager;
use RebelCode\Spotlight\Instagram\Utils\Arrays;

class Server
{
    /** @var Engine */
    protected $engine;

    /** @var FeedManager */
    protected $feedManager;

    /**
     * Constructor.
     *
     * @param Engine      $engine
     * @param FeedManager $feedManager
     */
    public function __construct(Engine $engine, FeedManager $feedManager)
    {
        $this->engine = $engine;
        $this->feedManager = $feedManager;
    }

    public function getFeedMedia(array $options = [], ?int $from = 0, int $num = null): array
    {
        // Check if numPosts is not a responsive value first
        $num = !is_array($options['numPosts'] ?? null) ? $options['numPosts'] : null;
        // Otherwise get the desktop value, defaulting to 9
        $num = $num ?? ($options['numPosts']['desktop'] ?? 9);

        // Get media and total
        $feed = $this->feedManager->createFeed($options);
        $result = $this->engine->aggregate($feed, $num, $from);
        $media = ItemAggregator::getCollection($result, ItemAggregator::DEF_COLLECTION);
        $total = $result->data[ItemAggregator::DATA_TOTAL];

        WpPostMediaStore::updateLastRequestedTime($result->items);

        $needImport = false;
        foreach ($result->data[ItemAggregator::DATA_CHILDREN] as $child) {
            if (count($child['result']->items) === 0) {
                $needImport = true;
                break;
            }
        }

        // Get stories
        $storyFeed = StoryFeed::createFromFeed($feed);
        $storiesResult = $this->engine->aggregate($storyFeed);
        $stories = ItemAggregator::getCollection($storiesResult, ItemAggregator::DEF_COLLECTION);

        return [
            'media' => $media,
            'stories' => $stories,
            'total' => $total,
            'needImport' => $needImport,
            'errors' => Arrays::map($result->errors, function (Error $error) {
                return (array) $error;
            }),
        ];
    }

    public function import(array $options): array
    {
        $feed = $this->feedManager->createFeed($options);

        $result = new Result();
        foreach ($feed->sources as $id => $source) {
            $subResult = $this->engine->import($source);
            $result->items = array_merge($result->items, $subResult->items);
            $result->errors = array_merge($result->errors, $subResult->errors);
        }

        return [
            'success' => $result->success,
            'items' => $result->items,
            'data' => $result->data,
            'errors' => Arrays::map($result->errors, function (Error $error) {
                return (array) $error;
            }),
        ];
    }
}
