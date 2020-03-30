<?php

namespace PayPal\Checkout\Http;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

abstract class PaypalRequest extends Request implements RequestInterface
{
}
