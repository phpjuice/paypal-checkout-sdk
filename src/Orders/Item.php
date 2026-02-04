<?php

namespace PayPal\Checkout\Orders;

use Brick\Money\Exception\UnknownCurrencyException;
use PayPal\Checkout\Concerns\CastsToJson;
use PayPal\Checkout\Contracts\Amount as AmountContract;
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
    use CastsToJson;

    /**
     * The item name or title.
     */
    protected string $name;

    /**
     * The item price or rate per unit.
     * If you specify unit_amount, purchase_units[].amount.breakdown.item_total is required.
     * Must equal unit_amount * quantity for all items.
     */
    protected AmountContract $unit_amount;

    /**
     * The item tax for each unit.
     * If tax is specified, purchase_units[].amount.breakdown.tax_total is required.
     * Must equal tax * quantity for all items.
     */
    protected ?AmountContract $tax = null;

    /**
     * The item quantity. Must be a whole number.
     */
    protected int $quantity;

    /**
     * The stock keeping unit (SKU) for the item.
     */
    protected string $sku;

    /**
     * The detailed item description.
     */
    protected string $description = '';

    /**
     * The item category type. The possible values are:.The item category type. The possible values are:
     *     - DIGITAL_GOODS. Goods that are stored, delivered, and used in their electronic format.
     *     - PHYSICAL_GOODS. A tangible item that can be shipped with proof of delivery.
     */
    protected string $category = DIGITAL_GOODS;

    /**
     * The URL of the item's image.
     */
    protected ?string $image_url = null;

    /**
     * create a new item instance.
     */
    public function __construct(string $name, AmountContract $amount, int $quantity = 1)
    {
        $this->name = $name;
        $this->unit_amount = $amount;
        $this->quantity = $quantity;
        $this->sku = $this->setSku(uniqid());
    }

    /**
     * create a new item instance.
     *
     * @throws UnknownCurrencyException
     */
    public static function create(string $name, string $value, string $currency_code = 'USD', int $quantity = 1): Item
    {
        $amount = Amount::of($value, $currency_code);

        return new self($name, $amount, $quantity);
    }

    /**
     * set's item amount.
     */
    public function setUnitAmount(AmountContract $unit_amount): self
    {
        $this->unit_amount = $unit_amount;

        return $this;
    }

    /**
     * return's item sku.
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * set's item sku.
     */
    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * return's item sku.
     */
    public function getAmount(): AmountContract
    {
        return $this->unit_amount;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $payload = [
            'name' => $this->getName(),
            'unit_amount' => $this->unit_amount->toArray(),
            'quantity' => $this->getQuantity(),
            'description' => $this->getDescription(),
            'category' => $this->getCategory(),
        ];

        if ($this->image_url !== null) {
            $payload['image_url'] = $this->image_url;
        }

        return $payload;
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
        if (! in_array($category, $validOptions)) {
            throw new InvalidItemCategoryException;
        }
        $this->category = $category;

        return $this;
    }

    /**
     * return's item image url.
     */
    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    /**
     * set's item image url.
     */
    public function setImageUrl(?string $image_url): self
    {
        $this->image_url = $image_url;

        return $this;
    }
}
