<?php

namespace PayPal\Checkout\Orders;

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-Amount_breakdown.
 */
class AmountBreakdown extends Amount
{
    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return [
            'currency_code' => $this->getCurrencyCode(),
            'value' => $this->getValue(),
            'breakdown' => [
                'item_total' => [
                    'currency_code' => $this->getCurrencyCode(),
                    'value' => $this->getValue(),
                ],
            ],
        ];
    }
}
