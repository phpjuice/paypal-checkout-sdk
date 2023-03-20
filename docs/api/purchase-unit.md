## PurchaseUnit

Namespace: `PayPal\Checkout\Orders`

- Uses Traits:
  - `CastsToJson`
  - `HasCollection`

### Properties

- **amount** `AmountBreakdown`

  The total order Amount with an optional breakdown that provides details, such as the total item Amount, total tax Amount, shipping, handling, insurance, and discounts, if any.

- **items** `Item[]`

  An array of items that the customer purchases from the merchant.

### Methods

- **__construct(AmountBreakdown $amount): void**

  Create a new instance of the `PurchaseUnit` class.

  - **Parameters**
    - `$amount` `AmountBreakdown` - The total order Amount with an optional breakdown that provides details, such as the total item Amount, total tax Amount, shipping, handling, insurance, and discounts, if any.

- **addItems(array $items): self**

  Push a new item into the `items` array.

  - **Parameters**
    - `$items` `Item[]` - An array of `Item` objects representing the items to be added.
  - **Returns**
    - `PurchaseUnit` - The updated `PurchaseUnit` object.
  - **Throws**
    - `MultiCurrencyOrderException` - If the currency code of the `Item` being added does not match the currency code of the `PurchaseUnit`.

- **addItem(Item $item): self**

  Push a new item into the `items` array.

  - **Parameters**
    - `$item` `Item` - The `Item` object to be added.
  - **Returns**
    - `PurchaseUnit` - The updated `PurchaseUnit` object.
  - **Throws**
    - `MultiCurrencyOrderException` - If the currency code of the `Item` being added does not match the currency code of the `PurchaseUnit`.

- **getItems(): array**

  Get the `items` array.

  - **Returns**
    - `Item[]` - An array of `Item` objects.

- **getAmount(): AmountBreakdown**

  Get the `amount` property.

  - **Returns**
    - `AmountBreakdown` - The `amount` property.

- **toArray(): array**

  Convert the `PurchaseUnit` object to an array.

  - **Returns**
    - `array` - An array representation of the `PurchaseUnit` object.
