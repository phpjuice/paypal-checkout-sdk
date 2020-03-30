<?php

namespace PayPal\Checkout\Orders;

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-Amount_breakdown.
 */
class AmountBreakdown extends Amount
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'currency_code' => $this->currency_code,
            'value' => $this->value,
            'breakdown' => [
                'item_total' => [
                    'currency_code' => $this->currency_code,
                    'value' => $this->value,
                ],
            ],
        ];
    }
}
