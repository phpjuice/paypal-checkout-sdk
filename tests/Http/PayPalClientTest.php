<?php

namespace Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PayPal\Checkout\Environment\ProductionEnvironment;
use PayPal\Checkout\Http\AccessTokenRequest;
use PayPal\Checkout\Http\OrderCaptureRequest;
use PayPal\Checkout\Http\PayPalClient;
use PHPUnit\Framework\TestCase;

class PayPalClientTest extends TestCase
{
    /**
     * @var \PayPal\Checkout\Environment\ProductionEnvironment
     */
    protected $environment = null;

    protected $httpClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->environment = new ProductionEnvironment('client_id', 'client_secret');

        $response1 = json_encode([
            'access_token' => 'A21AAFSO5otrlVigoJUQ1p',
            'token_type' => 'Bearer',
            'expires_in' => 32400,
        ]);

        $response2 = json_encode([
            'id' => '1KC5501443316171H',
            'intent' => 'CAPTURE',
            'status' => 'CREATED',
        ]);
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], $response1),
            new Response(200, ['Content-Type' => 'application/json'], $response2),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $this->httpClient = new Client(['handler' => $handlerStack]);
    }

    public function testFetchAccessToken()
    {
        $paypalClient = new PayPalClient($this->environment);
        $paypalClient->setClient($this->httpClient);
        $accessToken = $paypalClient->fetchAccessToken();
        $this->assertEquals('Bearer A21AAFSO5otrlVigoJUQ1p', $accessToken->authorizationString());
    }

    public function testHasAuthorizationHeader()
    {
        $paypalClient = new PayPalClient($this->environment);
        $paypalClient->setClient($this->httpClient);

        $request = new AccessTokenRequest($this->environment);

        $this->assertTrue($paypalClient->hasAuthHeader($request));

        $request = new OrderCaptureRequest('1KC5501443316171H');

        $this->assertFalse($paypalClient->hasAuthHeader($request));
    }

    public function testHasInvalidToken()
    {
        $paypalClient = new PayPalClient($this->environment);
        $paypalClient->setClient($this->httpClient);

        $this->assertTrue($paypalClient->hasInvalidToken());

        $paypalClient->fetchAccessToken();

        $this->assertFalse($paypalClient->hasInvalidToken());
    }

    public function testSendRequest()
    {
        $paypalClient = new PayPalClient($this->environment);
        $paypalClient->setClient($this->httpClient);

        $request = new OrderCaptureRequest('1KC5501443316171H');

        $response = $paypalClient->send($request);

        $result = json_decode((string) $response->getBody());
        $this->assertEquals('1KC5501443316171H', $result->id);
    }
}
