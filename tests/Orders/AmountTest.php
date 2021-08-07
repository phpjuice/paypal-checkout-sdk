<?php

namespace Tests\Orders;

use Brick\Money\Exception\UnknownCurrencyException;
use PayPal\Checkout\Orders\Amount;
use PHPUnit\Framework\TestCase;

class AmountTest extends TestCase
{
    public function testToArray()
    {
        $expected = [
            'value' => "100.00",
            'currency_code' => 'CAD',
        ];
        $amount = new Amount("100.00", 'CAD');
        $this->assertEquals($expected, $amount->toArray());
    }

    public function testToJson()
    {
        $expectedJson = ' {
            "currency_code": "CAD",
            "value": "100.00"
        }';
        $amount = new Amount("100.00", 'CAD');
        $this->assertJsonStringEqualsJsonString($expectedJson, $amount->toJson());
    }

    /**
     * @throws UnknownCurrencyException
     */
    public function testAmountOf()
    {
        $amount = Amount::of("100.00", 'CAD');
        $this->assertEquals("100.00", $amount->getValue());
        $this->assertEquals("CAD", $amount->getCurrencyCode());
    }
}
