<?php

namespace PayPal\Checkout\Orders;

use PayPal\Checkout\Contracts\Money as MoneyContract;

/**
 * Money Value Object.
 *
 * Holds money specific data.
 */
class Money implements MoneyContract
{

    /**
     * @var Currency
     */
    protected $currency;

    /**
     * The value, which might be:
     *     - An integer for currencies like JPY that are not typically fractional.
     *     - A decimal fraction for currencies like TND that are subdivided into thousandths.
     *
     * @pattern ^((-?[0-9]+)|(-?([0-9]+)?[.][0-9]+))$
     *
     * @var float
     */
    protected $amount;

    /**
     * @param  float  $amount
     * @param  Currency|string  $currency
     */
    public function __construct(float $amount, $currency)
    {
        if (!($currency instanceof Currency)) {
            $currency = Currency::from($currency);
        }

        $this->currency = $currency;
        $this->amount = $amount;
    }

    /**
     * return amount's currency code.
     */
    public function getCurrencyCode(): string
    {
        return $this->currency->getCode();
    }


    /**
     * return amount's currency code.
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * gets amount value.
     */
    public function getAmount(): float
    {
        return $this->amount;
    }
}
