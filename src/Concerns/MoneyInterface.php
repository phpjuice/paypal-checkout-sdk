<?php

namespace PayPal\Checkout\Concerns;

use PayPal\Checkout\Orders\Currency;

interface MoneyInterface
{
    /**
     * return money's currency code.
     */
    public function getCurrency(): Currency;

    /**
     * return money's currency code.
     */
    public function getCurrencyCode(): string;

    /**
     * gets amount value.
     */
    public function getAmount(): float;
}
