<?php

namespace PayPal\Checkout\Environment;

class ProductionEnvironment extends PayPalEnvironment
{
    public function __construct($clientId, $clientSecret)
    {
        parent::__construct($clientId, $clientSecret);
    }

    /**
     * @return string
     */
    public function baseUrl(): string
    {
        return 'https://api.paypal.com';
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'production';
    }
}
