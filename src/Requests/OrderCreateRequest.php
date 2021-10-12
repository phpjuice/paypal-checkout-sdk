<?php

namespace PayPal\Checkout\Requests;

use GuzzleHttp\Psr7\Utils;
use PayPal\Checkout\Orders\Order;
use PayPal\Http\PaypalRequest;

class OrderCreateRequest extends PaypalRequest
{
    public function __construct(Order $order = null)
    {
        $headers = [
            'Prefer' => 'return=representation',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $body = Utils::streamFor((string) $order);
        parent::__construct('POST', '/v2/checkout/orders', $headers, $body);
    }
}
