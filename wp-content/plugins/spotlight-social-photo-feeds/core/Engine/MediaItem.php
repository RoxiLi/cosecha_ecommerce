<?php

namespace RebelCode\Spotlight\Instagram\Engine;

use DateTime;
use Exception;
use RebelCode\Iris\Item;
use RebelCode\Spotlight\Instagram\Engine\Sources\StorySource;
use RebelCode\Spotlight\Instagram\IgApi\IgMedia;

class MediaItem
{
    // FROM INSTAGRAM API
    // -----------------------
    const MEDIA_ID = 'media_id';
    const CAPTION = 'caption';
    const USERNAME = 'username';
    const TIMESTAMP = 'timestamp';
    const MEDIA_TYPE = 'media_type';
    const MEDIA_URL = 'media_url';
    const MEDIA_PRODUCT_TYPE = 'media_product_type';
    const PERMALINK = 'permalink';
    const SHORTCODE = 'shortcode';
    const VIDEO_TITLE = 'video_title';
    const THUMBNAIL_URL = 'thumbnail_url';
    const LIKES_COUNT = 'like_count';
    const COMMENTS_COUNT = 'comments_count';
    const COMMENTS = 'comments';
    const CHILDREN = 'children';
    // CUSTOM FIELDS
    // -----------------------
    const POST = 'post';
    const IS_STORY = 'is_story';
    const LAST_REQUESTED = 'last_requested';
    const THUMBNAILS = 'thumbnails';
    const MEDIA_SIZE = 'media_size';
    const SOURCE_TYPE = 'source_type';
    const SOURCE_NAME = 'source_name';

    /**
     * Checks if a media instance is an expired story.
     *
     * @since 0.6
     *
     * @param Item $media The media to check.
     *
     * @return bool True if the story is expired, false if not. True is also returned in the post has an invalid date.
     */
    public static function isExpiredStory(Item $media): bool
    {
        if ($media->data[static::SOURCE_TYPE] !== StorySource::TYPE) {
            return false;
        }

        try {
            $datetime = new DateTime($media->data[static::TIMESTAMP]);
            $diff = time() - $datetime->getTimestamp();

            return $diff > IgMedia::STORY_MAX_LIFE;
        } catch (Exception $exception) {
            return true;
        }
    }
}
