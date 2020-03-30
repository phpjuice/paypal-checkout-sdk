<?php

namespace Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use function GuzzleHttp\json_decode;
use GuzzleHttp\Psr7\Response;
use PayPal\Checkout\Environment\ProductionEnvironment;
use PayPal\Checkout\Http\AccessTokenRequest;
use PHPUnit\Framework\TestCase;

class AccessTokenRequestTest extends TestCase
{
    /**
     * @var \PayPal\Checkout\Environment\ProductionEnvironment
     */
    protected $environment = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->environment = new ProductionEnvironment('client_id', 'client_secret');
    }

    public function testHasCorrectUri()
    {
        $request = new AccessTokenRequest($this->environment);
        $this->assertEquals('/v1/oauth2/token', $request->getUri());
    }

    public function testHasCorrectMethod()
    {
        $request = new AccessTokenRequest($this->environment);
        $this->assertEquals('POST', $request->getMethod());
    }

    public function testHasCorrectHeaders()
    {
        $request = new AccessTokenRequest($this->environment);
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
    }

    public function testHasBasicAuthHeaders()
    {
        $request = new AccessTokenRequest($this->environment);
        $this->assertEquals('Basic '.$this->environment->basicAuthorizationString(), $request->getHeaderLine('Authorization'));
    }

    public function testHasCorrectDataWithGetBody()
    {
        $request = new AccessTokenRequest($this->environment);
        $expected = http_build_query(['grant_type' => 'client_credentials']);
        $this->assertEquals($expected, (string) $request->getBody());
    }

    public function testExecuteRequest()
    {
        $mockResponse = json_encode([
            'access_token' => 'A21AAFSO5otrlVigoJUQ1p',
            'token_type' => 'Bearer',
            'expires_in' => 32400,
        ]);
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], $mockResponse),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $response = $client->send(new AccessTokenRequest($this->environment));

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode((string) $response->getBody());
        $this->assertEquals('A21AAFSO5otrlVigoJUQ1p', $body->access_token);
        $this->assertEquals('Bearer', $body->token_type);
        $this->assertEquals(32400, $body->expires_in);
    }
}
