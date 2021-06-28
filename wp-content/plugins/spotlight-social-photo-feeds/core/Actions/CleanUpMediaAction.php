<?php

namespace RebelCode\Spotlight\Instagram\Actions;

use RebelCode\Spotlight\Instagram\Config\ConfigSet;
use RebelCode\Spotlight\Instagram\Engine\MediaItem;
use RebelCode\Spotlight\Instagram\Engine\Sources\StorySource;
use RebelCode\Spotlight\Instagram\Engine\Stores\WpPostMediaStore;
use RebelCode\Spotlight\Instagram\PostTypes\MediaPostType;
use RebelCode\Spotlight\Instagram\Utils\Arrays;
use RebelCode\Spotlight\Instagram\Wp\PostType;

/**
 * The action that cleans up old media.
 *
 * @since 0.1
 */
class CleanUpMediaAction
{
    /**
     * Config key for the age limit.
     *
     * @since 0.1
     */
    const CFG_AGE_LIMIT = 'cleanerAgeLimit';

    /**
     * @since 0.1
     *
     * @var PostType
     */
    protected $cpt;

    /**
     * @since 0.1
     *
     * @var ConfigSet
     */
    protected $config;

    /**
     * Constructor.
     *
     * @since 0.1
     *
     * @param PostType  $cpt    The media post type.
     * @param ConfigSet $config The config set.
     */
    public function __construct(PostType $cpt, ConfigSet $config)
    {
        $this->cpt = $cpt;
        $this->config = $config;
    }

    /**
     * @since 0.1
     *
     * @param string|null $ageLimit Optional age limit override, to ignore the saved config value.
     *
     * @return int The number of deleted posts.
     */
    public function __invoke(?string $ageLimit = null)
    {
        set_time_limit(3600);

        $count = 0;

        // Delete media according to the age limit
        {
            $ageLimit = $ageLimit ?? $this->config->get(static::CFG_AGE_LIMIT)->getValue();
            $ageTime = strtotime($ageLimit . ' ago');

            $oldMedia = $this->cpt->query([
                'meta_query' => [
                    [
                        'key' => MediaPostType::LAST_REQUESTED,
                        'compare' => '<=',
                        'value' => $ageTime,
                    ],
                ],
            ]);

            $count += count($oldMedia);
            Arrays::each($oldMedia, [MediaPostType::class, 'deleteMedia']);
        }

        // Delete expired stories
        {
            $stories = $this->cpt->query([
                'meta_query' => [
                    [
                        'key' => MediaPostType::IS_STORY,
                        'compare' => '!=',
                        'value' => '',
                    ],
                ],
            ]);

            foreach ($stories as $story) {
                $item = WpPostMediaStore::wpPostToItem($story, StorySource::create(''));

                if (MediaItem::isExpiredStory($item)) {
                    $count++;
                    MediaPostType::deleteMedia($story);
                }
            }
        }

        return $count;
    }
}
