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
     * @var Money
     */
    protected $money;

    /**
     * create a new amount instance.
     */
    public function __construct(string $currency_code, float $value)
    {
        $this->money = new Money($value, $currency_code);
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
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->money->getCurrencyCode();
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->money->getAmount();
    }
}
