<?php

namespace Tests\Requests;

use GuzzleHttp\Utils;
use PayPal\Checkout\Requests\OrderCreateRequest;
use PayPal\Checkout\Orders\AmountBreakdown;
use PayPal\Checkout\Orders\ApplicationContext;
use PayPal\Checkout\Orders\Item;
use PayPal\Checkout\Orders\Order;
use PayPal\Checkout\Orders\PurchaseUnit;

it("has correct request uri", function () {
    $request = new OrderCreateRequest();
    expect((string) $request->getUri())->toBe('/v2/checkout/orders');
});

it("has correct request method", function () {
    $request = new OrderCreateRequest();
    expect($request->getMethod())->toBe('POST');
});

it("has correct request headers", function () {
    $request = new OrderCreateRequest();
    expect($request->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($request->getHeaderLine('Prefer'))->toBe('return=representation');
});

it("has correct request body", function () {
    // Arrange
    /** @noinspection PhpUnhandledExceptionInspection */
    $amount = AmountBreakdown::of('100.00');
    $purchase_unit = new PurchaseUnit($amount);
    /** @noinspection PhpUnhandledExceptionInspection */
    $purchase_unit->addItem(Item::create('Item 1', '100.00'));

    $application_context = new ApplicationContext('Paypal Inc', 'en');
    $application_context->setUserAction('PAY_NOW');
    $application_context->setReturnUrl('https://site.com/payment/return');
    $application_context->setCancelUrl('https://site.com/payment/cancel');

    $order = new Order('CAPTURE');
    $order->addPurchaseUnit($purchase_unit);
    $order->setApplicationContext($application_context);

    // Act
    $request = new OrderCreateRequest($order);

    // Assert
    expect((string) $request->getBody())->toBe((string) $order);
    expect(Utils::jsonDecode($request->getBody(), true))->toBe($order->toArray());
});

it("can execute request", function () {
    // Arrange
    /** @noinspection PhpUnhandledExceptionInspection */
    $amount = AmountBreakdown::of('100.00');
    $purchase_unit = new PurchaseUnit($amount);
    /** @noinspection PhpUnhandledExceptionInspection */
    $purchase_unit->addItem(Item::create('Item 1', '100.00'));

    $application_context = new ApplicationContext('Paypal Inc', 'en');
    $application_context->setUserAction('PAY_NOW');
    $application_context->setReturnUrl('https://site.com/payment/return');
    $application_context->setCancelUrl('https://site.com/payment/cancel');

    $order = new Order('CAPTURE');
    $order->addPurchaseUnit($purchase_unit);
    $order->setApplicationContext($application_context);

    $client = mockCreateOrderResponse();

    // Act
    /** @noinspection PhpUnhandledExceptionInspection */
    $response = $client->send(new OrderCreateRequest($order));

    // Asserts
    expect($response->getStatusCode())->toBe(200);
    $result = Utils::jsonDecode((string) $response->getBody(), true);
    expect($result)->toBe([
        'id' => '1KC5501443316171H',
        'intent' => 'CAPTURE',
        'status' => 'CREATED'
    ]);
});
