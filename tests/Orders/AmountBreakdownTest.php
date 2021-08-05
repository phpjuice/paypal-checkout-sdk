<?php

namespace Tests\Orders;

use PayPal\Checkout\Orders\AmountBreakdown;
use PayPal\Checkout\Orders\Currency;
use PayPal\Checkout\Orders\Money;
use PHPUnit\Framework\TestCase;

class AmountBreakdownTest extends TestCase
{
    public function testToArray()
    {
        $currency = Currency::from('USD');

        $expected = [
            'currency_code' => 'USD',
            'value' => 100.00,
            'breakdown' => [
                'item_total' => [
                    'currency_code' => 'USD',
                    'value' => 100.00,
                ],
            ],
        ];
        $amount = new AmountBreakdown($currency->getCode(), 100.00);
        $this->assertEquals($expected, $amount->toArray());

        $expected = [
            'currency_code' => $currency->getCode(),
            'value' => 100.00,
            'breakdown' => [
                'item_total' => [
                    'currency_code' => $currency->getCode(),
                    'value' => 150.00,
                ],
                'discount' => [
                    'currency_code' => $currency->getCode(),
                    'value' => 50.00,
                ],
            ],
        ];
        $amount = new AmountBreakdown($currency->getCode(), 100.00);
        $amount->setItemTotal(new Money(150, $currency));
        $amount->setDiscount(new Money(50, $currency));
        $this->assertEquals($expected, $amount->toArray());
    }

    public function testToJson()
    {
        $currency = Currency::from('USD');

        $expectedJson = [
            "currency_code" => "USD",
            "value" => 100.00,
            "breakdown" => [
                "item_total" => [
                    "currency_code" => "USD",
                    "value" => 100.00
                ]
            ]
        ];
        $amount = new AmountBreakdown('USD', 100.00);
        $this->assertJsonStringEqualsJsonString(json_encode($expectedJson), $amount->toJson());


        $expectedJson = [
            "currency_code" => "USD",
            "value" => 100.00,
            "breakdown" => [
                "item_total" => [
                    "currency_code" => "USD",
                    "value" => 150.00
                ],
                "discount" => [
                    "currency_code" => "USD",
                    "value" => 50.00
                ]
            ]
        ];
        $amount = new AmountBreakdown($currency->getCode(), 100.00);
        $amount->setItemTotal(new Money(150, $currency));
        $amount->setDiscount(new Money(50, $currency));
        $this->assertEquals(json_encode($expectedJson), $amount->toJson());
    }
}
