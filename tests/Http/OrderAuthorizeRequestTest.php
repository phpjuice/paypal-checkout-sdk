<?php

namespace Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Utils;
use PayPal\Checkout\Http\OrderAuthorizeRequest;

it('has correct request uri', function () {
    $request = new OrderAuthorizeRequest('1KC5501443316171H');
    expect((string) $request->getUri())->toBe('/v2/checkout/orders/1KC5501443316171H/authorize');
});

it('has correct request method', function () {
    $request = new OrderAuthorizeRequest('1KC5501443316171H');
    expect($request->getMethod())->toBe('POST');
});

it('has correct request headers', function () {
    $request = new OrderAuthorizeRequest('1KC5501443316171H');
    expect($request->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($request->getHeaderLine('Prefer'))->toBe('return=representation');
});

it('can execute request', function () {
    $mockResponse = json_encode([
        'id' => '1KC5501443316171H',
    ]);
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], $mockResponse),
    ]);
    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    try {
        $response = $client->send(new OrderAuthorizeRequest('1KC5501443316171H'));
        expect($response->getStatusCode())->toBe(200);
        $result = Utils::jsonDecode((string) $response->getBody(), true);
        expect($result['id'])->toBe('1KC5501443316171H');
    } catch (GuzzleException $e) {
    }
});
