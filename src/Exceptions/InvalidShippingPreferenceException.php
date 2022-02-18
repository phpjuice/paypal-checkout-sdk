<?php

namespace PayPal\Checkout\Exceptions;

use RuntimeException;

class InvalidShippingPreferenceException extends RuntimeException
{
    /** @var string */
    protected $message = 'Shipping preference provided is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#definition-order_application_context.';
}
