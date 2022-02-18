<?php

namespace PayPal\Checkout\Exceptions;

use RuntimeException;

class InvalidOrderIntentException extends RuntimeException
{
    /** @var string */
    protected $message = 'Order intent provided is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#orders_create.';
}
