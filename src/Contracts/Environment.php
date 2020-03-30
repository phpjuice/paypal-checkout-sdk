<?php

namespace PayPal\Checkout\Contracts;

interface Environment
{
    /**
     * @return string
     */
    public function baseUrl();

    /**
     * @return string
     */
    public function basicAuthorizationString();
}
