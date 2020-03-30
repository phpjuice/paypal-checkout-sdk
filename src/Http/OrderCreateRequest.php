<?php

namespace PayPal\Checkout\Http;

use function GuzzleHttp\Psr7\stream_for;
use PayPal\Checkout\Orders\Order;

class OrderCreateRequest extends PaypalRequest
{
    public function __construct(Order $order = null)
    {
        $headers = [
            'Prefer' => 'return=representation',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $body = stream_for((string) $order);
        parent::__construct('POST', '/v2/checkout/orders', $headers, $body);
    }
}
