<?php

/** @noinspection SpellCheckingInspection */

namespace Tests\Orders;

use PayPal\Checkout\Orders\Payee;

it('can initialize a payee', function () {
    // Arrange
    $expected = [
        'email_address' => 'payee@paypal.com',
        'merchant_id' => 'YP568Y95AVSDY',
    ];

    // Act
    $payee = Payee::create('payee@paypal.com', 'YP568Y95AVSDY');

    // Assert
    expect($payee->toArray())->toBe($expected);
});

it('casts to an array', function () {
    // Arrange
    $expected = [
        'email_address' => 'payee@paypal.com',
        'merchant_id' => 'YP568Y95AVSDY',
    ];

    // Act
    $payee = new Payee('payee@paypal.com', 'YP568Y95AVSDY');

    // Assert
    expect($payee->toArray())->toBe($expected);
});

it('casts to json', function () {
    // Arrange
    $expected = json_encode([
        'email_address' => 'payee@paypal.com',
        'merchant_id' => 'YP568Y95AVSDY',
    ]);

    // Act
    $payee = Payee::create('payee@paypal.com', 'YP568Y95AVSDY');

    // Assert
    expect($payee->toJson())->toBe($expected);
});
