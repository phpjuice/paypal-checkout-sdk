<?php

namespace PayPal\Checkout\Orders;

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-Amount_breakdown.
 */
class AmountBreakdown extends Amount
{

    /**
     * The subtotal for all items. Required if the request includes purchase_units[].items[].unit_amount.
     * Must equal the sum of (items[].unit_amount * items[].quantity) for all items.
     * item_total.value can not be a negative number.
     * @var float
     */
    protected $item_total;


    /**
     * The discount for all items within a given purchase_unit. discount.value can not be a negative number.
     * @var float
     */
    protected $discount;


    /**
     * create a new amount instance.
     */
    public function __construct(string $currency_code, float $value, float $item_total, float $discount = 0.0)
    {
        $this->currency_code = $currency_code;
        $this->value = $value;
        $this->item_total = $item_total;
        $this->discount = $discount;
    }


    /**
     * return's item_total.
     * @noinspection PhpUnused
     */
    public function getItemTotal(): float
    {
        return $this->item_total;
    }

    /**
     * return's discount.
     * @noinspection PhpUnused
     */
    public function getDiscount() : float
    {
        return $this->discount;
    }

    /**
     * it's sets item_total
     */
    public function setItemTotal(float $item_total)
    {
        return $this->item_total = $item_total;
    }
    /**
     * it's sets discount
     */
    public function setDiscount(float $discount)
    {
        return $this->discount = $discount;
    }

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
                    'value' => $this->getItemTotal(),
                ],
                'discount' => [
                    'currency_code' => $this->getCurrencyCode(),
                    'value' => $this->getDiscount(),
                ]
            ],
        ];
    }
}
