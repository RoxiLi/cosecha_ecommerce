<?php

namespace RebelCode\Spotlight\Instagram\RestApi\EndPoints\Feeds;

use RebelCode\Iris\Source;
use RebelCode\Spotlight\Instagram\PostTypes\MediaPostType;
use RebelCode\Spotlight\Instagram\RestApi\EndPoints\AbstractEndpointHandler;
use RebelCode\Spotlight\Instagram\Utils\Arrays;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Handler for the endpoint that provides the sources used across all feeds.
 *
 * @since 0.5.3
 */
class GetSourcesEndpoint extends AbstractEndpointHandler
{
    /**
     * @inheritDoc
     *
     * @since 0.5.3
     */
    protected function handle(WP_REST_Request $request)
    {
        $sources = MediaPostType::getUsedSources();
        $response = Arrays::map($sources, function (Source $source) {
            return [
                'type' => $source->type,
                'name' => $source->data['name'] ?? '',
            ];
        });

        return new WP_REST_Response($response);
    }
}
