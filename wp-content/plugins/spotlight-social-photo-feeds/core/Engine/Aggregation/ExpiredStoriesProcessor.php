<?php

namespace RebelCode\Spotlight\Instagram\Engine\Aggregation;

use RebelCode\Iris\Aggregation\ItemFeed;
use RebelCode\Iris\Aggregation\ItemProcessor;
use RebelCode\Spotlight\Instagram\Engine\MediaItem;
use RebelCode\Spotlight\Instagram\Utils\Functions;

/**
 * Filters media to remove expired stories.
 *
 * @since 0.6
 */
class ExpiredStoriesProcessor implements ItemProcessor
{
    /**
     * @inheritDoc
     *
     * @since 0.6
     */
    public function process(array &$items, ItemFeed $feed)
    {
        $items = array_filter($items, Functions::not([MediaItem::class, 'isExpiredStory']));
    }
}
