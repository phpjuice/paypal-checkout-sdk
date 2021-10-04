<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Orders;

use Brick\Money\Currency;
use Brick\Money\Money;
use PayPal\Checkout\Orders\AmountBreakdown;

it("can cast to an array", function () {
    // Arrange
    $currency = Currency::of('USD');
    $expected = [
        'currency_code' => $currency->getCurrencyCode(),
        'value' => "100.00",
        'breakdown' => [
            'item_total' => [
                'currency_code' => $currency->getCurrencyCode(),
                'value' => "100.00",
            ],
        ],
    ];

    // Act
    $amount = new AmountBreakdown("100.00", $currency->getCurrencyCode());

    // Assert
    expect($amount->toArray())->toBe($expected);
});


it("can cast to an array with a discount", function () {
    // Arrange
    $currency = Currency::of('USD');
    $expected = [
        'currency_code' => $currency->getCurrencyCode(),
        'value' => "100.00",
        'breakdown' => [
            'item_total' => [
                'currency_code' => $currency->getCurrencyCode(),
                'value' => "150.00",
            ],
            'discount' => [
                'currency_code' => $currency->getCurrencyCode(),
                'value' => "50.00",
            ],
        ],
    ];

    // Act
    $amount = new AmountBreakdown("100.00", $currency->getCurrencyCode());
    $amount->setItemTotal(Money::of("150", $currency));
    $amount->setDiscount(Money::of("50", $currency));

    // Assert
    expect($amount->toArray())->toBe($expected);
});


it("can cast to json", function () {
    // Arrange
    $currency = Currency::of('USD');
    $expectedJson = json_encode([
        "currency_code" => $currency->getCurrencyCode(),
        "value" => "100.00",
        "breakdown" => [
            "item_total" => [
                "currency_code" => $currency->getCurrencyCode(),
                "value" => "100.00"
            ]
        ]
    ]);

    // Act
    $amount = new AmountBreakdown("100.00", $currency->getCurrencyCode());

    // Assert
    expect($amount->toJson())->toBe($expectedJson);
});


it("can cast to json with a discount", function () {
    // Arrange
    $currency = Currency::of('USD');
    $expectedJson = json_encode([
        "currency_code" => "USD",
        "value" => "100.00",
        "breakdown" => [
            "item_total" => [
                "currency_code" => "USD",
                "value" => "150.00"
            ],
            "discount" => [
                "currency_code" => "USD",
                "value" => "50.00"
            ]
        ]
    ]);

    // Act
    $amount = new AmountBreakdown("100.00", $currency->getCurrencyCode());
    $amount->setItemTotal(Money::of("150", $currency));
    $amount->setDiscount(Money::of("50", $currency));

    // Assert
    expect($amount->toJson())->toBe($expectedJson);
});
