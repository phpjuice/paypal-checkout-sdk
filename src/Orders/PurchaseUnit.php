<?php

namespace PayPal\Checkout\Orders;

use PayPal\Checkout\Concerns\HasCollection;
use PayPal\Checkout\Concerns\HasJson;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;
use PayPal\Checkout\Exceptions\ItemTotalMismatchException;
use PayPal\Checkout\Exceptions\MultiCurrencyOrderException;

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-purchase_unit.
 */
class PurchaseUnit implements Arrayable, Jsonable
{
    use HasJson;
    use HasCollection;

    /**
     * The total order Amount with an optional breakdown that provides details,
     * such as the total item Amount, total tax Amount, shipping, handling, insurance,
     * and discounts, if any.
     *
     * @var AmountBreakdown
     */
    protected $amount;

    /**
     * An array of items that the customer purchases from the merchant.
     *
     * @var Item[]
     */
    protected $items = [];

    /**
     * Create a new collection.
     */
    public function __construct(string $currency_code, float $value)
    {
        $this->amount = new AmountBreakdown($currency_code, $value);
    }

    /**
     *  push a new item into items array.
     */
    public function addItem(Item $item): self
    {
        if ($item->getAmount()->getCurrencyCode() != $this->amount->getCurrencyCode()) {
            throw new MultiCurrencyOrderException();
        }

        $this->items[] = $item;

        return $this;
    }

    /**
     * return's purchase unit items.
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * return's the purchase unit amount breakdown.
     */
    public function getAmount(): AmountBreakdown
    {
        return $this->amount;
    }

    /**
     * convert a purchase unit instance to array.
     */
    public function toArray(): array
    {
        $realAmount = $this->amount->getValue();
        $calculatedAmount = $this->getCalculatedAmount();
        $epsilon = 0.00001;

        if (abs($calculatedAmount - $realAmount) > $epsilon) {
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

    /**
     * return's recalculated amount of the purchase unit.
     */
    public function getCalculatedAmount(): float
    {
        $itemsTotal = (float) array_reduce(
            $this->items,
            function ($totalAmount, Item $item) {
                $amount = $item->getAmount();
                $quantity = $item->getQuantity();
                $totalAmount += $amount->getValue() * $quantity;

                return $totalAmount;
            },
            0
        );

        $discount = $this->amount->hasDiscount() ? $this->amount->getDiscount()->getAmount() : 0;

        return $itemsTotal - $discount;
    }
}
