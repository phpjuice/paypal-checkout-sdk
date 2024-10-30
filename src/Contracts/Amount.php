<?php

namespace PayPal\Checkout\Contracts;

interface Amount
{
    /**
     * convert amount to an array.
     */
    public function toArray(): array;

    public function getCurrencyCode(): string;

    public function getValue(): string;

    /**
     * Convert the object to its JSON representation.
     */
    public function toJson(int $options = 0): string;
}
