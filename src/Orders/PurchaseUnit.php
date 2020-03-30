<?php

namespace PayPal\Checkout\Orders;

use ArrayAccess;
use PayPal\Checkout\Concerns\HasCollection;
use PayPal\Checkout\Concerns\HasJson;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;
use PayPal\Checkout\Exceptions\ItemTotalMismatchException;
use PayPal\Checkout\Exceptions\MultiCurrencyOrderException;

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-purchase_unit.
 */
class PurchaseUnit implements Arrayable, Jsonable, ArrayAccess
{
    use HasJson;
    use HasCollection;

    /**
     * The total order Amount with an optional breakdown that provides details,
     * such as the total item Amount, total tax Amount, shipping, handling, insurance,
     * and discounts, if any.
     *
     * @var \PayPal\Checkout\Orders\AmountBreakdown
     */
    protected $amount;

    /**
     * An array of items that the customer purchases from the merchant.
     *
     * @var \PayPal\Checkout\Orders\Item[]
     */
    protected $items;

    /**
     * Create a new collection.
     *
     * @param string $currency_code
     * @param string $value
     *
     * @return self
     */
    public function __construct($currency_code, $value)
    {
        $this->amount = new AmountBreakdown($currency_code, $value);
    }

    /**
     *  Push an item onto the end of the purchase unit.
     */
    public function addItem(Item $item)
    {
        if ($item->getAmount()->getCurrencyCode() != $this->amount->getCurrencyCode()) {
            throw new MultiCurrencyOrderException();
        }

        $this->items[] = $item;

        return $this;
    }

    /**
     * getter for purchase unit items.
     *
     * @return Item[] $items
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * getter for purchase unit Amount.
     *
     * @return Amount $amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * sets Amount's currency code.
     *
     * @param string $currency_code
     */
    public function setCurrencyCode($currency_code)
    {
        $this->amount->setCurrencyCode($currency_code);

        return $this;
    }

    /**
     * sets Amount value.
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->amount->setValue($value);

        return $this;
    }

    /**
     * getter for purchase unit Amount.
     *
     * @return int $amount
     */
    public function getCalculatedAmount()
    {
        return array_reduce(
            $this->items,
            function ($totalAmount, Item $item) {
                $amount = $item->getAmount();
                $quantity = $item->getQuantity();
                $totalAmount += $amount->getValue() * $quantity;

                return $totalAmount;
            },
            0
        );
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        if ($this->getCalculatedAmount() !== $this->amount->getValue()) {
            throw new ItemTotalMismatchException();
        }

        return [
            'amount' => $this->amount->toArray(),
            'items' => array_map(
                function (Item $item) {
                    return $item->toArray();
                },
                $this->items
            ),
        ];
    }
}
