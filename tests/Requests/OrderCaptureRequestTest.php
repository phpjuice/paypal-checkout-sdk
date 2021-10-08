<?php

namespace Tests\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Utils;
use PayPal\Checkout\Requests\OrderCaptureRequest;

it("has correct request uri", function () {
    $request = new OrderCaptureRequest('1KC5501443316171H');
    expect((string) $request->getUri())->toBe('/v2/checkout/orders/1KC5501443316171H/capture');
});

it("has correct request method", function () {
    $request = new OrderCaptureRequest('1KC5501443316171H');
    expect($request->getMethod())->toBe('POST');
});

it("has correct request headers", function () {
    $request = new OrderCaptureRequest('1KC5501443316171H');

    expect($request->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($request->getHeaderLine('Prefer'))->toBe('return=representation');
});

it("can execute request", function () {
    $mockResponse = Utils::jsonEncode([
        'id' => '1KC5501443316171H',
    ]);
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], $mockResponse),
    ]);
    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    /** @noinspection PhpUnhandledExceptionInspection */
    $response = $client->send(new OrderCaptureRequest('1KC5501443316171H'));

    expect($response->getStatusCode())->toBe(200);

    $result = Utils::jsonDecode((string) $response->getBody(), true);

    expect($result['id'])->toBe('1KC5501443316171H');
});
