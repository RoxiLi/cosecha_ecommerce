<?php

namespace RebelCode\Spotlight\Instagram\RestApi\EndPoints\Accounts;

use Exception;
use RebelCode\Spotlight\Instagram\IgApi\AccessToken;
use RebelCode\Spotlight\Instagram\IgApi\IgAccount;
use RebelCode\Spotlight\Instagram\IgApi\IgApiClient;
use RebelCode\Spotlight\Instagram\PostTypes\AccountPostType;
use RebelCode\Spotlight\Instagram\RestApi\EndPoints\AbstractEndpointHandler;
use RebelCode\Spotlight\Instagram\Wp\PostType;
use WP_REST_Request;
use WP_REST_Response;

class ConnectAccountEndPoint extends AbstractEndpointHandler
{
    /**
     * @since 0.1
     *
     * @var IgApiClient
     */
    protected $client;

    /**
     * @since 0.1
     *
     * @var callable
     */
    protected $cpt;

    /**
     * Constructor.
     *
     * @since 0.1
     *
     * @param IgApiClient $client The API client.
     * @param PostType    $cpt    The accounts post type.
     */
    public function __construct(IgApiClient $client, PostType $cpt)
    {
        $this->client = $client;
        $this->cpt = $cpt;
    }

    /**
     * @inheritDoc
     *
     * @since 0.1
     *
     * @throws Exception
     */
    protected function handle(WP_REST_Request $request)
    {
        // Get the access token code from the request
        $tokenCode = filter_var($request['accessToken'], FILTER_SANITIZE_STRING);
        if (empty($tokenCode)) {
            return new WP_REST_Response(['error' => "Access token is required"], 400);
        }

        // Construct the access token object
        $accessToken = new AccessToken($tokenCode, 0);

        try {
            // FOR BUSINESS ACCOUNT ACCESS TOKENS
            if (stripos($tokenCode, 'EA') === 0 && strlen($tokenCode) > 145) {
                $userId = filter_var($request['userId'], FILTER_SANITIZE_STRING);

                if (empty($userId)) {
                    return new WP_REST_Response(['error' => 'The user ID is required for business accounts'], 400);
                }

                $account = $this->client->getGraphApi()->getAccountForUser($userId, $accessToken);
            } else {
                // FOR PERSONAL ACCOUNT ACCESS TOKENS
                $user = $this->client->getBasicApi()->getTokenUser($accessToken);
                $account = new IgAccount($user, $accessToken);
            }
            // Insert the account into the database (or update existing account)
            $accountId = AccountPostType::insertOrUpdate($this->cpt, $account);

            return new WP_REST_Response([
                'success' => true,
                'accountId' => $accountId,
            ]);
        } catch (Exception $e) {
            if (stripos($e->getMessage(), '#190') !== false) {
                return new WP_REST_Response(['error' => 'Invalid access token'], 400);
            } else {
                throw $e;
            }
        }
    }
}
