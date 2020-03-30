<?php

namespace PayPal\Checkout\Orders;

use PayPal\Checkout\Concerns\HasJson;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;
use PayPal\Checkout\Exceptions\InvalidItemCategoryException;

const DIGITAL_GOODS = 'DIGITAL_GOODS';
const PHYSICAL_GOODS = 'PHYSICAL_GOODS';

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-item.
 */
class Item implements Arrayable, Jsonable
{
    use HasJson;

    /**
     * The item name or title.
     *
     * @var string
     */
    protected $name;

    /**
     * The item price or rate per unit.
     * If you specify unit_amount, purchase_units[].amount.breakdown.item_total is required.
     * Must equal unit_amount * quantity for all items.
     *
     * @var \PayPal\Checkout\Orders\Amount
     */
    protected $unit_amount;

    /**
     * The item tax for each unit.
     * If tax is specified, purchase_units[].amount.breakdown.tax_total is required.
     * Must equal tax * quantity for all items.
     *
     * @var \PayPal\Checkout\Orders\Amount
     */
    protected $tax;

    /**
     * The item quantity. Must be a whole number.
     *
     * @var int
     */
    protected $quantity;

    /**
     * The stock keeping unit (SKU) for the item.
     *
     * @var string
     */
    protected $sku;

    /**
     * The detailed item description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * The item category type. The possible values are:.The item category type. The possible values are:
     *     - DIGITAL_GOODS. Goods that are stored, delivered, and used in their electronic format.
     *     - PHYSICAL_GOODS. A tangible item that can be shipped with proof of delivery.
     *
     * @var enum
     */
    protected $category = DIGITAL_GOODS;

    /**
     * Create a new collection.
     *
     * @param string $name
     * @param string $currency_code
     * @param float  $value
     * @param int    $quantity
     *
     * @return void
     */
    public function __construct($name, $currency_code, $value, $quantity = 1)
    {
        $this->name = $name;
        $this->unit_amount = new Amount($currency_code, $value);
        $this->quantity = $quantity;
        $this->sku = uniqid();
    }

    /**
     * set's item name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * set's item Amount.
     */
    public function setUnitAmount(Amount $unit_amount)
    {
        $this->unit_amount = $unit_amount;

        return $this;
    }

    /**
     * set's item quantity.
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * set's item description.
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * set's item category.
     */
    public function setCategory($category)
    {
        $validOptions = [DIGITAL_GOODS, PHYSICAL_GOODS];
        if (!in_array($category, $validOptions)) {
            throw new InvalidItemCategoryException();
        }
        $this->category = $category;

        return $this;
    }

    /**
     * return's item sku.
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * return's item sku.
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * return's item sku.
     */
    public function getAmount()
    {
        return $this->unit_amount;
    }

    /**
     * get's item quantity.
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'unit_amount' => $this->unit_amount->toArray(),
            'quantity' => $this->quantity,
            'description' => $this->description,
            'category' => $this->category,
        ];
    }
}
