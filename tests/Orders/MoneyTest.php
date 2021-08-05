<?php

namespace Tests\Orders;

use PayPal\Checkout\Orders\Currency;
use PayPal\Checkout\Orders\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function testReturnsTheRightAmount()
    {
        $money = new Money(100.00, new Currency('USD'));
        $this->assertEquals(100.00, $money->getAmount());
    }

    public function testReturnsTheRightCurrency()
    {
        $currency = new Currency('USD');
        $money = new Money(100.00, $currency);
        $this->assertEquals($currency, $money->getCurrency());
        $this->assertEquals('USD', $money->getCurrencyCode());
    }

    public function testItAcceptCurrencyCodeAsParam()
    {
        $money = new Money(100.00, 'USD');
        $this->assertInstanceOf(Currency::class, $money->getCurrency());
        $this->assertEquals('USD', $money->getCurrencyCode());
    }
}
