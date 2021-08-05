<?php

namespace PayPal\Checkout\Contracts;

use PayPal\Checkout\Orders\Currency;

interface Money
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
