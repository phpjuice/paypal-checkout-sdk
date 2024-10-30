<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Orders;

use Brick\Money\Money;
use PayPal\Checkout\Exceptions\InvalidOrderException;
use PayPal\Checkout\Exceptions\InvalidOrderIntentException;
use PayPal\Checkout\Orders\AmountBreakdown;
use PayPal\Checkout\Orders\ApplicationContext;
use PayPal\Checkout\Orders\Item;
use PayPal\Checkout\Orders\Order;
use PayPal\Checkout\Orders\PurchaseUnit;

it('can not create an order with invalid intent', function () {
    new Order('Invalid Intent');
})->throws(
    InvalidOrderIntentException::class,
    // phpcs:ignore
    'Order intent provided is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#orders_create.'
);

it('can not set an invalid intent on an order', function () {
    $order = new Order;
    $order->setIntent('invalid intent');
})->throws(
    InvalidOrderIntentException::class,
    // phpcs:ignore
    'Order intent provided is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#orders_create.'
);

it('can initialize an order', function () {
    // Act
    $order = new Order;

    // Assert
    expect($order->getIntent())->toBe('CAPTURE');
    expect($order->getApplicationContext()->toArray())->toBe([
        'locale' => 'en-US',
        'shipping_preference' => 'NO_SHIPPING',
        'landing_page' => 'NO_PREFERENCE',
        'user_action' => 'CONTINUE',
    ]);

    // Act
    $order->setIntent('AUTHORIZE');

    // Assert
    expect($order->getIntent())->toBe('AUTHORIZE');
    expect($order->getPurchaseUnits())->toBeEmpty();
});

it('can add a purchase unit to an order', function () {
    // Arrange
    $amount = AmountBreakdown::of('200.00', 'EUR');
    $purchase_unit = new PurchaseUnit($amount);
    $order = new Order;

    // Act
    $order->addPurchaseUnit($purchase_unit);

    // Assert
    expect(count($order->getPurchaseUnits()))->toBe(1);
});

it('can not add multiple purchase units to an order', function () {
    $amount = AmountBreakdown::of('200.00', 'EUR');
    $purchase_unit = new PurchaseUnit($amount);
    $order = new Order;

    // Act
    $order->addPurchaseUnit($purchase_unit);
    $order->addPurchaseUnit($purchase_unit);
})->throws(
    InvalidOrderException::class,
    'At present only 1 purchase_unit is supported.'
);

it('asserts an order has at least one purchase unit', function () {
    (new Order)->toArray();
})->throws(
    InvalidOrderException::class,
    'Paypal orders must have 1 purchase_unit at least.'
);

it('casts to an array', function () {
    // Arrange
    $amount = AmountBreakdown::of('250.00');
    $amount->setItemTotal(Money::of('300', 'USD'));
    $amount->setDiscount(Money::of('50', 'USD'));
    $purchase_unit = new PurchaseUnit($amount);

    $purchase_unit->addItem(Item::create('Item 1', '100.00'));
    $purchase_unit->addItem(Item::create('Item 2', '100.00', 'USD', 2));

    $application_context = new ApplicationContext('Paypal Inc', 'en');
    $application_context->setUserAction('PAY_NOW');
    $application_context->setReturnUrl('https://site.com/payment/return');
    $application_context->setCancelUrl('https://site.com/payment/cancel');

    // Act
    $order = new Order('CAPTURE');
    $order->addPurchaseUnit($purchase_unit);
    $order->setApplicationContext($application_context);

    // Assert
    $expected = [
        'intent' => 'CAPTURE',
        'purchase_units' => [
            [
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => '250.00',
                    'breakdown' => [
                        'item_total' => [
                            'currency_code' => 'USD',
                            'value' => '300.00',
                        ],
                        'discount' => [
                            'currency_code' => 'USD',
                            'value' => '50.00',
                        ],
                    ],
                ],
                'items' => [
                    [
                        'name' => 'Item 1',
                        'unit_amount' => [
                            'currency_code' => 'USD',
                            'value' => '100.00',
                        ],
                        'quantity' => 1,
                        'description' => '',
                        'category' => 'DIGITAL_GOODS',
                    ],
                    [
                        'name' => 'Item 2',
                        'unit_amount' => [
                            'currency_code' => 'USD',
                            'value' => '100.00',
                        ],
                        'quantity' => 2,
                        'description' => '',
                        'category' => 'DIGITAL_GOODS',
                    ],
                ],
            ],
        ],
        'application_context' => [
            'brand_name' => 'Paypal Inc',
            'locale' => 'en',
            'shipping_preference' => 'NO_SHIPPING',
            'landing_page' => 'NO_PREFERENCE',
            'user_action' => 'PAY_NOW',
            'return_url' => 'https://site.com/payment/return',
            'cancel_url' => 'https://site.com/payment/cancel',
        ],
    ];
    expect($order->toArray())->toBe($expected);
});

it('casts to json', function () {
    // Arrange
    $amount = AmountBreakdown::of('250.00');
    $amount->setItemTotal(Money::of('300', 'USD'));
    $amount->setDiscount(Money::of('50', 'USD'));
    $purchase_unit = new PurchaseUnit($amount);

    $purchase_unit->addItem(Item::create('Item 1', '100.00'));
    $purchase_unit->addItem(Item::create('Item 2', '100.00', 'USD', 2));

    $application_context = new ApplicationContext('Paypal Inc', 'en');
    $application_context->setUserAction('PAY_NOW');
    $application_context->setReturnUrl('https://site.com/payment/return');
    $application_context->setCancelUrl('https://site.com/payment/cancel');

    // Act
    $order = new Order('CAPTURE');
    $order->addPurchaseUnit($purchase_unit);
    $order->setApplicationContext($application_context);

    // Assert
    $expected = json_encode([
        'intent' => 'CAPTURE',
        'purchase_units' => [
            [
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => '250.00',
                    'breakdown' => [
                        'item_total' => [
                            'currency_code' => 'USD',
                            'value' => '300.00',
                        ],
                        'discount' => [
                            'currency_code' => 'USD',
                            'value' => '50.00',
                        ],
                    ],
                ],
                'items' => [
                    [
                        'name' => 'Item 1',
                        'unit_amount' => [
                            'currency_code' => 'USD',
                            'value' => '100.00',
                        ],
                        'quantity' => 1,
                        'description' => '',
                        'category' => 'DIGITAL_GOODS',
                    ],
                    [
                        'name' => 'Item 2',
                        'unit_amount' => [
                            'currency_code' => 'USD',
                            'value' => '100.00',
                        ],
                        'quantity' => 2,
                        'description' => '',
                        'category' => 'DIGITAL_GOODS',
                    ],
                ],
            ],
        ],
        'application_context' => [
            'brand_name' => 'Paypal Inc',
            'locale' => 'en',
            'shipping_preference' => 'NO_SHIPPING',
            'landing_page' => 'NO_PREFERENCE',
            'user_action' => 'PAY_NOW',
            'return_url' => 'https://site.com/payment/return',
            'cancel_url' => 'https://site.com/payment/cancel',
        ],
    ]);
    expect($order->toJson())->toBe($expected);
});
