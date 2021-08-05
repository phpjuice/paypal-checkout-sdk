<?php

namespace PayPal\Checkout\Orders;

use PayPal\Checkout\Concerns\HasJson;
use PayPal\Checkout\Contracts\Jsonable;
use PayPal\Checkout\Contracts\Arrayable;

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-Amount_breakdown.
 */
class AmountBreakdown  implements Arrayable, Jsonable
{
    use HasJson;

    /**
     * The three-character ISO-4217 currency code that identifies the currency.
     *
     * @var string
     */
    protected $currency_code;

    /**
     * The value, which might be:
     *     - An integer for currencies like JPY that are not typically fractional.
     *     - A decimal fraction for currencies like TND that are subdivided into thousandths.
     *
     * @pattern ^((-?[0-9]+)|(-?([0-9]+)?[.][0-9]+))$
     *
     * @var float
     */
    protected $value;

    /**
     * The subtotal for all items. Required if the request includes purchase_units[].items[].unit_amount.
     * Must equal the sum of (items[].unit_amount * items[].quantity) for all items.
     * item_total.value can not be a negative number.
     * @var Amount
     */
    protected $item_total;

    /**
     * The discount for all items within a given purchase_unit. discount.value can not be a negative number.
     * @var Amount
     */
    protected $discount;



    /**
     * create a new AmountBreakdown instance.
     */
    public function __construct(string $currency_code, float $value)
    {
        $this->currency_code = $currency_code;
        $this->value = $value;
        $this->item_total =  new Amount($currency_code, $value);
    }

    /**
     * return's currency_code.
     * @noinspection PhpUnused
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currency_code;
    }

    /**
     * return's value.
     * @noinspection PhpUnused
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

     /**
     * return's item_total.
     * @noinspection PhpUnused
     * @return Amount
     */
    public function getItemTotal()
    {
        return $this->item_total;
    }


      /**
     * return's discount.
     * @noinspection PhpUnused
     * @return Amount
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    public function setCurrencyCode(string $currency_code)
    {
        return $this->currency_code = $currency_code;
    }

    public function setValue(float $value)
    {
        return $this->value = $value;
    }


    public function setItemTotal(Amount $item_total)
    {
        $this->item_total = $item_total;
    }

    public function setDiscount(Amount $discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return bool
     */
    public function hasDiscount()
    {
        return $this->discount ? true : false;
    }
    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $data =  [
            'currency_code' => $this->getCurrencyCode(),
            'value' => $this->getValue(),
            'breakdown' => [
                'item_total' => [
                    'currency_code' => $this->getItemTotal()->getCurrencyCode(),
                    'value' => $this->getItemTotal()->getValue(),
                ],
            ],
        ];

        if ($this->hasDiscount()) {
            $data['breakdown']['discount'] = [
                'currency_code' => $this->getDiscount()->getCurrencyCode(),
                'value' => $this->getDiscount()->getValue(),
            ];
        }

        return $data;
    }
}
