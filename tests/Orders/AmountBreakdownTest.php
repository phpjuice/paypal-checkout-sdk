<?php

namespace Tests\Orders;

use PayPal\Checkout\Orders\AmountBreakdown;
use PHPUnit\Framework\TestCase;

class AmountBreakdownTest extends TestCase
{
    public function testToArray()
    {
        $expected = [
            'currency_code' => 'USD',
            'value' => 150.00,
            'breakdown' => [
                'item_total' => [
                    'currency_code' => 'USD',
                    'value' => 150.00,
                ],
                'discount' => [
                    'currency_code' => 'USD',
                    'value' => 0.00,
                ],
            ],
        ];
        $amount = new AmountBreakdown('USD', 150.00, 150.00);
        $this->assertEquals($expected, $amount->toArray());



        $expected = [
            'currency_code' => 'USD',
            'value' => 100.00,
            'breakdown' => [
                'item_total' => [
                    'currency_code' => 'USD',
                    'value' => 150.00,
                ],
                'discount' => [
                    'currency_code' => 'USD',
                    'value' => 50.00,
                ],
            ],
        ];
        $amount = new AmountBreakdown('USD', 100.00, 150.00, 50.00);
        $this->assertEquals($expected, $amount->toArray());
    }

    public function testToJson()
    {
        $expectedJson = ' {
            "currency_code": "USD",
            "value": 150.00,
            "breakdown":{
                "item_total" : {
                    "currency_code": "USD",
                    "value": 150.00
                },
                "discount" : {
                    "currency_code": "USD",
                    "value": 0.00
                }
            }
        }';
        $amount = new AmountBreakdown('USD', 150.00, 150.00);
        $this->assertJsonStringEqualsJsonString($expectedJson, $amount->toJson());
    }
}
