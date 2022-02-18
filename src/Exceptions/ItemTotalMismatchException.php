<?php

namespace PayPal\Checkout\Exceptions;

use RuntimeException;

class ItemTotalMismatchException extends RuntimeException
{
    /** @var string */
    protected $message = 'Items Total Should equal sum of (unit_amount * quantity) across all items for a given purchase_unit';
}
