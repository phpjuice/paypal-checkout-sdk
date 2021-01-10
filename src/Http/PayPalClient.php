<?php

namespace PayPal\Checkout\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Utils;
use PayPal\Checkout\Contracts\Environment;
use PayPal\Checkout\Contracts\HttpClient;

class PayPalClient implements HttpClient
{
    /**
     * Paypal environment (sandbox|production).
     *
     * @var \PayPal\Checkout\Contracts\Environment
     */
    protected $environment;

    /**
     * Http client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Access Token.
     *
     * @var \PayPal\Checkout\Http\AccessToken
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
        $this->client = new \GuzzleHttp\Client(['base_uri' => $environment->baseUrl()]);
    }

    /**
     * Send an http request.
     *
     * @return \GuzzleHttp\Psr7\Response
     */
    public function send(Request $request)
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
     * Injects default user-agent into the request.
     *
     * @return \GuzzleHttp\Psr7\Request
     */
    public function injectUserAgentHeaders(Request $request)
    {
        return $request->withHeader('User-Agent', 'PayPalHttp-PHP HTTP/1.1');
    }

    /**
     * Inject gzip headers into the request.
     *
     * @return \GuzzleHttp\Psr7\Request
     */
    public function injectGzipHeaders(Request $request)
    {
        return $request->withHeader('Accept-Encoding', 'gzip');
    }

    /**
     * Inject paypal sdk headers into request.
     *
     * @return \GuzzleHttp\Psr7\Request
     */
    public function injectSdkHeaders(Request $request)
    {
        return $request->withHeader('sdk_name', 'Checkout SDK')
                    ->withHeader('sdk_version', '1.0.0')
                    ->withHeader('sdk_tech_stack', 'PHP '.PHP_VERSION);
    }

    /**
     * Returns default user-agent.
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Send an http request.
     *
     * @return bool
     */
    public function hasAuthHeader(Request $request)
    {
        return array_key_exists('Authorization', $request->getHeaders());
    }

    /**
     * Send an http request.
     *
     * @return bool
     */
    public function hasInvalidToken()
    {
        return is_null($this->access_token) || $this->access_token->isExpired();
    }

    /**
     * Send an http request.
     *
     * @return \PayPal\Checkout\Http\AccessToken
     */
    public function fetchAccessToken()
    {
        $response = $this->client->send(new AccessTokenRequest($this->environment));
        $result = Utils::jsonDecode((string) $response->getBody());
        $this->access_token = new AccessToken($result->access_token, $result->token_type, $result->expires_in);

        return $this->access_token;
    }
}
