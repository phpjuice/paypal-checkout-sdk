<?php

namespace Tests\Orders;

use PayPal\Checkout\Orders\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function testItReturnsTheRightCode()
    {
        $currency = new Currency('USD');
        $this->assertEquals('USD', $currency->getCode());
    }

    public function testCurrencyCodeIsAlwaysUpperCase()
    {
        $currency = new Currency('usd');
        $this->assertEquals('USD', $currency->getCode());
    }

    public function testItCanCreateCurrencyInstanceFromString()
    {
        $currency = Currency::from('USD');
        $this->assertInstanceOf(Currency::class, $currency);
        $this->assertEquals('USD', $currency->getCode());
    }
}
