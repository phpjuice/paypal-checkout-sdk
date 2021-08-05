<?php

namespace PayPal\Checkout\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Utils;
use PayPal\Checkout\Contracts\Environment;
use PayPal\Checkout\Contracts\HttpClient;

class PayPalClient implements HttpClient
{
    /**
     * Paypal environment (sandbox|production).
     *
     * @var Environment
     */
    protected $environment;

    /**
     * Http client.
     *
     * @var Client
     */
    protected $client;

    /**
     * Access Token.
     *
     * @var AccessToken
     */
    protected $access_token;

    /**
     * HttpClient constructor. Pass the environment you wish to make calls to.
     *
     * @param $environment Environment
     *
     * @see Environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
        $this->client = new Client(['base_uri' => $environment->baseUrl()]);
    }

    /**
     * Send http request.
     *
     * @param  Request  $request
     * @return Response
     * @throws GuzzleException
     */
    public function send(Request $request): Response
    {
        // if request doesn't have a authorization header
        if (!$this->hasAuthHeader($request)) {
            // fetch access token if null or expired
            if ($this->hasInvalidToken()) {
                $this->fetchAccessToken();
            }
            // add Authorization header to request
            $request = $request->withHeader('Authorization', $this->access_token->authorizationString());
        }

        // add user agent header to request
        $request = $this->injectUserAgentHeaders($request);

        // add sdk headers
        $request = $this->injectSdkHeaders($request);

        // add gzip headers
        $request = $this->injectGzipHeaders($request);

        // send request and return response
        return $this->client->send($request);
    }

    /**
     * Check if headers contain an auth header.
     *
     * @param  Request  $request
     * @return bool
     */
    public function hasAuthHeader(Request $request): bool
    {
        return array_key_exists('Authorization', $request->getHeaders());
    }

    /**
     * Check if request has a valid token
     *
     * @return bool
     */
    public function hasInvalidToken(): bool
    {
        return is_null($this->access_token) || $this->access_token->isExpired();
    }

    /**
     * Sends a request that fetches the access token.
     *
     * @return AccessToken
     * @throws GuzzleException
     */
    public function fetchAccessToken(): AccessToken
    {
        $response = $this->client->send(new AccessTokenRequest($this->environment));
        $result = Utils::jsonDecode((string) $response->getBody());
        $this->access_token = new AccessToken($result->access_token, $result->token_type, $result->expires_in);

        return $this->access_token;
    }

    /**
     * Injects default user-agent into the request.
     *
     * @param  Request  $request
     * @return Request
     */
    public function injectUserAgentHeaders(Request $request): Request
    {
        return $request->withHeader('User-Agent', 'PayPalHttp-PHP HTTP/1.1');
    }

    /**
     * Inject paypal sdk headers into request.
     *
     * @param  Request  $request
     * @return Request
     */
    public function injectSdkHeaders(Request $request): Request
    {
        $r = $request->withHeader('sdk_name', 'Checkout SDK')
            ->withHeader('sdk_version', '1.0.0');

        /*
         * Only inject this header on production
         *
         * @see https://github.com/phpjuice/paypal-checkout-sdk/issues/6
         */
        if ('production' == $this->environment->name()) {
            $r = $r->withHeader('sdk_tech_stack', 'PHP '.PHP_VERSION);
        }

        return $r;
    }

    /**
     * Inject gzip headers into the request.
     *
     * @param  Request  $request
     * @return Request
     */
    public function injectGzipHeaders(Request $request): Request
    {
        return $request->withHeader('Accept-Encoding', 'gzip');
    }

    /**
     * Returns default user-agent.
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
