<?php

namespace PayPal\Checkout\Orders;

use PayPal\Checkout\Concerns\HasJson;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-Amount_breakdown.
 */
class Amount implements Arrayable, Jsonable
{
    use HasJson;

    /**
     * The three-character ISO-4217 currency code that identifies the currency.
     *
     * @var string
     */
    protected $currency_code;

    /**
     * The value, which might be:
     *     - An integer for currencies like JPY that are not typically fractional.
     *     - A decimal fraction for currencies like TND that are subdivided into thousandths.
     *
     * @pattern ^((-?[0-9]+)|(-?([0-9]+)?[.][0-9]+))$
     *
     * @var float
     */
    protected $value;

    /**
     * Create a new collection.
     *
     * @param string $currency_code
     * @param float  $value
     *
     * @return void
     */
    public function __construct($currency_code, $value)
    {
        $this->currency_code = $currency_code;
        $this->value = $value;
    }

    /**
     * gets Amount value.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * gets Amount's currency code.
     */
    public function getCurrencyCode()
    {
        return $this->currency_code;
    }

    /**
     * sets Amount value.
     *
     * @param float $value
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * sets Amount's currency code.
     *
     * @param string $currency_code
     */
    public function setCurrencyCode($currency_code)
    {
        $this->currency_code = $currency_code;

        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'currency_code' => $this->currency_code,
            'value' => $this->value,
        ];
    }
}
