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
     * create a new amount instance.
     */
    public function __construct(string $currency_code, float $value)
    {
        $this->currency_code = $currency_code;
        $this->value = $value;
    }

    /**
     * convert amount to an array.
     */
    public function toArray(): array
    {
        return [
            'currency_code' => $this->getCurrencyCode(),
            'value' => $this->getValue(),
        ];
    }

    /**
     * return amount's currency code.
     */
    public function getCurrencyCode(): string
    {
        return $this->currency_code;
    }

    /**
     * sets amount's currency code.
     */
    public function setCurrencyCode(string $currency_code): self
    {
        $this->currency_code = $currency_code;

        return $this;
    }

    /**
     * gets amount value.
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * sets amount value.
     */
    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }
}
