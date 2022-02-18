<?php

namespace PayPal\Checkout\Exceptions;

use RuntimeException;

class InvalidItemCategoryException extends RuntimeException
{
    /** @var string */
    protected $message = 'Item category is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#definition-item.';
}
