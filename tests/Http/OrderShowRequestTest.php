<?php

namespace Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Utils;
use PayPal\Checkout\Http\OrderShowRequest;
use PHPUnit\Framework\TestCase;

class OrderShowRequestTest extends TestCase
{
    public function testHasCorrectUri()
    {
        $request = new OrderShowRequest('1KC5501443316171H');
        $this->assertEquals('/v2/checkout/orders/1KC5501443316171H', $request->getUri());
    }

    public function testHasCorrectMethod()
    {
        $request = new OrderShowRequest('1KC5501443316171H');
        $this->assertEquals('GET', $request->getMethod());
    }

    public function testHasCorrectHeaders()
    {
        $request = new OrderShowRequest('1KC5501443316171H');
        $this->assertEquals('application/json', $request->getHeaderLine('Content-Type'));
        $this->assertEquals('return=representation', $request->getHeaderLine('Prefer'));
    }

    public function testExecuteRequest()
    {
        $mockResponse = Utils::jsonEncode([
            'id' => '1KC5501443316171H',
            'intent' => 'CAPTURE',
            'status' => 'CREATED',
        ]);
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], $mockResponse),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $response = $client->send(new OrderShowRequest('1KC5501443316171H'));

        $this->assertEquals(200, $response->getStatusCode());

        $result = Utils::jsonDecode((string) $response->getBody());
        $this->assertEquals('1KC5501443316171H', $result->id);
        $this->assertEquals('CAPTURE', $result->intent);
        $this->assertEquals('CREATED', $result->status);
    }
}
