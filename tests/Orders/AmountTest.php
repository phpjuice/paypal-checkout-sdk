<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Orders;

use Brick\Money\Exception\UnknownCurrencyException;
use PayPal\Checkout\Orders\Amount;

it("can initialize amount", function () {
    // Arrange
    $value = "100.00";
    $currency_code = "USD";

    // Act
    $amount = new Amount($value, $currency_code);

    // Assert
    expect($amount->getValue())->toBe($value);
    expect($amount->getCurrencyCode())->toBe($currency_code);
});

it("can initialize amount using of", function () {
    // Arrange
    $value = "100.00";
    $currency_code = "USD";

    // Act
    $amount = Amount::of($value, $currency_code);

    // Assert
    expect($amount->getValue())->toBe($value);
    expect($amount->getCurrencyCode())->toBe($currency_code);
});

it("can cast to an array", function () {
    // Arrange
    $expected = [
        'currency_code' => 'CAD',
        'value' => "100.00",
    ];

    // Act
    $amount = new Amount("100.00", 'CAD');

    // Assert
    expect($amount->toArray())->toBe($expected);
});

it("can cast to an array using of", function () {
    // Arrange
    $expected = [
        'currency_code' => 'CAD',
        'value' => "100.00",
    ];

    // Act
    $amount = Amount::of("100.00", 'CAD');

    // Assert
    expect($amount->toArray())->toBe($expected);
});

it("can cast to json", function () {
    $expectedJson = '{"currency_code":"CAD","value":"100.00"}';
    $amount = new Amount("100.00", 'CAD');
    expect($amount->toJson())->toBe($expectedJson);
});

it("can cast to json using of", function () {
    $expectedJson = '{"currency_code":"CAD","value":"100.00"}';
    $amount = Amount::of("100.00", 'CAD');
    expect($amount->toJson())->toBe($expectedJson);
});

it("throws unknown currency exception", function () {
    Amount::of("100.00", 'R');
})->throws(UnknownCurrencyException::class);
