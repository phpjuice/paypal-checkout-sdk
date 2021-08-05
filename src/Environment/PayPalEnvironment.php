<?php

namespace PayPal\Checkout\Environment;

use PayPal\Checkout\Contracts\Environment;

abstract class PayPalEnvironment implements Environment
{
    /**
     * Paypal client id.
     *
     * @var string
     */
    protected $clientId;

    /**
     * PayPal client secret.
     *
     * @var string
     */
    protected $clientSecret;

    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function basicAuthorizationString(): string
    {
        return base64_encode($this->clientId.':'.$this->clientSecret);
    }
}
