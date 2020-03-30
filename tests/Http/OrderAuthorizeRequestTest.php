<?php

namespace Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PayPal\Checkout\Http\OrderAuthorizeRequest;
use PHPUnit\Framework\TestCase;

class OrderAuthorizeRequestTest extends TestCase
{
    public function testHasCorrectUri()
    {
        $request = new OrderAuthorizeRequest('1KC5501443316171H');
        $this->assertEquals('/v2/checkout/orders/1KC5501443316171H/authorize', $request->getUri());
    }

    public function testHasCorrectMethod()
    {
        $request = new OrderAuthorizeRequest('1KC5501443316171H');
        $this->assertEquals('POST', $request->getMethod());
    }

    public function testHasCorrectHeaders()
    {
        $request = new OrderAuthorizeRequest('1KC5501443316171H');
        $this->assertEquals('application/json', $request->getHeaderLine('Content-Type'));
        $this->assertEquals('return=representation', $request->getHeaderLine('Prefer'));
    }

    public function testExecuteRequest()
    {
        $mockResponse = json_encode([
            'id' => '1KC5501443316171H',
        ]);
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], $mockResponse),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $response = $client->send(new OrderAuthorizeRequest('1KC5501443316171H'));

        $this->assertEquals(200, $response->getStatusCode());

        $result = json_decode((string) $response->getBody());
        $this->assertEquals('1KC5501443316171H', $result->id);
    }
}
