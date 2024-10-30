<?php

namespace PayPal\Checkout\Contracts;

interface Arrayable
{
    /**
     * Get the instance as an array.
     */
    public function toArray(): array;
}
