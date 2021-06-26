<?php /** @noinspection SpellCheckingInspection */

namespace Tests\Orders;

use PayPal\Checkout\Orders\Payee;
use PHPUnit\Framework\TestCase;

class PayeeTest extends TestCase
{
    public function testToArray()
    {
        $expected = [
            'email_address' => 'payee@paypal.com',
            'merchant_id' => 'YP568Y95AVSDY',
        ];
        $payee = new Payee('payee@paypal.com', 'YP568Y95AVSDY');
        $this->assertEquals($expected, $payee->toArray());
    }

    public function testToJson()
    {
        $expectedJson = ' {
            "email_address": "payee@paypal.com",
            "merchant_id": "YP568Y95AVSDY"
        }';
        $payee = new Payee('payee@paypal.com', 'YP568Y95AVSDY');
        $this->assertJsonStringEqualsJsonString($expectedJson, $payee->toJson());
    }
}
