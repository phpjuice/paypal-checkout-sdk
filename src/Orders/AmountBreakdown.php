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
     * @var Money
     */
    protected $item_total;

    /**
     * The discount for all items within a given purchase_unit. discount.value can not be a negative number.
     * @var Money
     */
    protected $discount;

    /**
     * create a new AmountBreakdown instance.
     */
    public function __construct(string $currency_code, float $value)
    {
        parent::__construct($currency_code, $value);
        $this->item_total = new Money($value, $currency_code);
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $data = [
            'currency_code' => $this->getCurrencyCode(),
            'value' => $this->getValue(),
            'breakdown' => [
                'item_total' => [
                    'currency_code' => $this->item_total->getCurrencyCode(),
                    'value' => $this->item_total->getAmount(),
                ],
            ],
        ];

        if ($this->hasDiscount()) {
            $data['breakdown']['discount'] = [
                'currency_code' => $this->discount->getCurrencyCode(),
                'value' => $this->discount->getAmount(),
            ];
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function hasDiscount(): bool
    {
        return (bool) $this->discount;
    }

    /**
     * return's discount.
     * @return Money
     */
    public function getDiscount(): Money
    {
        return $this->discount;
    }

    public function setDiscount(Money $discount)
    {
        $this->discount = $discount;
    }

    /**
     * return's item_total.
     * @return Money
     */
    public function getItemTotal(): Money
    {
        return $this->item_total;
    }

    public function setItemTotal(Money $item_total)
    {
        $this->item_total = $item_total;
    }
}
