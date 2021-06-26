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
     * @var Amount
     */
    protected $unit_amount;

    /**
     * The item tax for each unit.
     * If tax is specified, purchase_units[].amount.breakdown.tax_total is required.
     * Must equal tax * quantity for all items.
     *
     * @var Amount
     */
    protected $tax = null;

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
     * @var string
     */
    protected $category = DIGITAL_GOODS;

    /**
     * create a new item instance.
     */
    public function __construct(string $name, string $currency_code, float $value, int $quantity = 1)
    {
        $this->name = $name;
        $this->unit_amount = new Amount($currency_code, $value);
        $this->quantity = $quantity;
        $this->sku = uniqid();
    }

    /**
     * set's item amount.
     */
    public function setUnitAmount(Amount $unit_amount): self
    {
        $this->unit_amount = $unit_amount;

        return $this;
    }

    /**
     * return's item sku.
     * @noinspection PhpUnused
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * set's item sku.
     * @noinspection PhpUnused
     */
    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * return's item sku.
     */
    public function getAmount(): Amount
    {
        return $this->unit_amount;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'unit_amount' => $this->unit_amount->toArray(),
            'quantity' => $this->getQuantity(),
            'description' => $this->getDescription(),
            'category' => $this->getCategory(),
        ];
    }

    /**
     * return's item name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * set's item name.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * return's item quantity.
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * set's item quantity.
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * return's item description.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * set's item description.
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * return's item category.
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * set's item category.
     */
    public function setCategory(string $category): self
    {
        $validOptions = [DIGITAL_GOODS, PHYSICAL_GOODS];
        if (!in_array($category, $validOptions)) {
            throw new InvalidItemCategoryException();
        }
        $this->category = $category;

        return $this;
    }
}
