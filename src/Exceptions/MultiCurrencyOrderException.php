<?php

namespace PayPal\Checkout\Exceptions;

use RuntimeException;

class MultiCurrencyOrderException extends RuntimeException
{
    /** @var string */
    protected $message = 'Multiple differing values of currency_code are not supported. Entire Order request must have the same currency_code.';
}
