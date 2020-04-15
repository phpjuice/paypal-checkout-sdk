<?php

namespace Tests\Orders;

use PayPal\Checkout\Orders\Amount;
use PHPUnit\Framework\TestCase;

class AmountTest extends TestCase
{
    public function testToArray()
    {
        $expected = [
            'value' => 100.00,
            'currency_code' => 'CAD',
        ];
        $amount = new Amount('CAD', 100.00);
        $this->assertEquals($expected, $amount->toArray());
    }

    public function testToJson()
    {
        $expectedJson = ' {
            "currency_code": "CAD",
            "value": 100.00
        }';
        $amount = new Amount('CAD', 100.00);
        $this->assertJsonStringEqualsJsonString($expectedJson, $amount->toJson());
    }
}
