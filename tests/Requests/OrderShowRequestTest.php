<?php

namespace Tests\Requests;

use GuzzleHttp\Utils;
use PayPal\Checkout\Requests\OrderShowRequest;

it("has correct request uri", function () {
    $request = new OrderShowRequest('1KC5501443316171H');
    expect((string) $request->getUri())->toBe('/v2/checkout/orders/1KC5501443316171H');
});

it("has correct request method", function () {
    $request = new OrderShowRequest('1KC5501443316171H');
    expect($request->getMethod())->toBe('GET');
});

it("has correct request headers", function () {
    $request = new OrderShowRequest('1KC5501443316171H');
    expect($request->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($request->getHeaderLine('Prefer'))->toBe('return=representation');
});

it("can execute request", function () {
    // Arrange
    $client = mockCreateOrderResponse();

    // Act
    /** @noinspection PhpUnhandledExceptionInspection */
    $response = $client->send(new OrderShowRequest('1KC5501443316171H'));
    expect($response->getStatusCode())->toBe(200);

    $result = Utils::jsonDecode((string) $response->getBody(), true);
    expect($result)->toBe([
        'id' => '1KC5501443316171H',
        'intent' => 'CAPTURE',
        'status' => 'CREATED'
    ]);
});
