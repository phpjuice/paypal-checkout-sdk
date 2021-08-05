<?php

namespace PayPal\Checkout\Orders;

use JsonSerializable;

/**
 * Currency Value Object.
 *
 * Holds Currency specific data.
 */
final class Currency implements JsonSerializable
{

    /**
     * The three-character ISO-4217 currency code that identifies the currency.
     *
     * @var string
     */
    private $code;


    public function __construct(string $code = 'USD')
    {
        $this->code = strtoupper($code);
    }

    /**
     * @param  string  $string
     * @return Currency
     */
    public static function from(string $string): Currency
    {
        return new self($string);
    }

    /**
     * Returns the currency code.
     *
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Checks whether this currency is the same as another.
     */
    public function equals(Currency $other): bool
    {
        return $this->code === $other->code;
    }

    public function __toString(): string
    {
        return $this->code;
    }

    public function jsonSerialize()
    {
        return $this->code;
    }
}
