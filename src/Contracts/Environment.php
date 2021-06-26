<?php

namespace PayPal\Checkout\Contracts;

interface Environment
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return string
     */
    public function baseUrl(): string;

    /**
     * @return string
     */
    public function basicAuthorizationString(): string;
}
