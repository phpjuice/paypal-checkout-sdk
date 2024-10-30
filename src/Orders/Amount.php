<?php

namespace PayPal\Checkout\Orders;

use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use PayPal\Checkout\Concerns\CastsToJson;
use PayPal\Checkout\Contracts\Amount as AmountContract;

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-Amount_breakdown.
 */
class Amount implements AmountContract
{
    use CastsToJson;

    /**
     * The three-character ISO-4217 currency code that identifies the currency.
     */
    protected Money $money;

    /**
     * create a new amount instance.
     *
     * @throws UnknownCurrencyException
     */
    public function __construct(string $value, string $currency_code = 'USD')
    {
        $this->money = Money::of($value, $currency_code);
    }

    /**
     * @throws UnknownCurrencyException
     */
    public static function of(string $value, string $currency_code = 'USD'): Amount
    {
        return new self($value, $currency_code);
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

    public function getCurrencyCode(): string
    {
        return $this->money->getCurrency()->getCurrencyCode();
    }

    public function getValue(): string
    {
        return (string) $this->money->getAmount();
    }
}
