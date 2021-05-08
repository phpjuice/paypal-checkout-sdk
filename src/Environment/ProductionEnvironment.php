<?php

namespace PayPal\Checkout\Environment;

class ProductionEnvironment extends PayPalEnvironment
{
    public function __construct($clientId, $clientSecret)
    {
        parent::__construct($clientId, $clientSecret);
    }

    public function baseUrl()
    {
        return 'https://api.paypal.com';
    }

    /**
     * @return string
     */
    public function name()
    {
        return 'production';
    }
}
