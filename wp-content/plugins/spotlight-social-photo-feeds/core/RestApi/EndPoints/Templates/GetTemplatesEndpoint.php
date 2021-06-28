<?php

declare(strict_types=1);

namespace RebelCode\Spotlight\Instagram\RestApi\EndPoints\Templates;

use RebelCode\Spotlight\Instagram\RestApi\EndPoints\AbstractEndpointHandler;
use RebelCode\Spotlight\Instagram\SaaS\SaasResourceFetcher;
use WP_REST_Request;
use WP_REST_Response;

class GetTemplatesEndpoint extends AbstractEndpointHandler
{
    /** @var SaasResourceFetcher */
    protected $provider;

    /**
     * Constructor.
     *
     * @param SaasResourceFetcher $provider
     */
    public function __construct(SaasResourceFetcher $provider)
    {
        $this->provider = $provider;
    }

    protected function handle(WP_REST_Request $request)
    {
        return new WP_REST_Response($this->provider->get());
    }
}
