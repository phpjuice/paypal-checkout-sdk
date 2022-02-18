<?php

namespace PayPal\Checkout\Exceptions;

use RuntimeException;

class InvalidUserActionException extends RuntimeException
{
    /** @var string */
    protected $message = 'User Action provided is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#definition-order_application_context.';
}
