<?php

namespace RebelCode\Spotlight\Instagram\RestApi\EndPoints\Media;

use RebelCode\Spotlight\Instagram\RestApi\EndPoints\AbstractEndpointHandler;
use RebelCode\Spotlight\Instagram\Server;
use WP_REST_Request;
use WP_REST_Response;

/**
 * The endpoint for importing media.
 *
 * @since 0.5
 */
class ImportMediaEndPoint extends AbstractEndpointHandler
{
    /**
     * @var Server
     */
    protected $server;

    /**
     * Constructor.
     *
     * @param Server $server
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * @inheritDoc
     *
     * @since 0.5
     */
    protected function handle(WP_REST_Request $request)
    {
        $options = $request->get_param('options') ?? [];
        $response = $this->server->import($options);

        return new WP_REST_Response($response);
    }
}
