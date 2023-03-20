# PurchaseUnit

See https://developer.paypal.com/docs/api/orders/v2/#definition-purchase_unit.

## Methods

### PurchaseUnit::__construct()

Creates a new PurchaseUnit instance.

#### Signature

```php
public function __construct(AmountBreakdown $amount);
```
#### Example

```php
$amount = AmountBreakdown::of('100.00', 'CAD');
$purchase_unit = new PurchaseUnit($amount);
```

### PurchaseUnit::addItems()

Pushes a new item or array of items into the items array of PurchaseUnit.

#### Signature

```php
public function addItems(array $items): self;
```

#### Example

```php
$amount = AmountBreakdown::of('100', 'CAD');
$purchase_unit = new PurchaseUnit($amount);

$items = array_map(function ($index) {
    return Item::create("Item $index", '100.00', 'CAD', $index);
}, [1, 2, 3]);


$purchase_unit->addItems($items);

```

### PurchaseUnit::addItem()

Pushes a new item into the items array of PurchaseUnit.

#### Signature

```php
public function addItem(Item $item): self;
```

#### Example

```php
$amount = AmountBreakdown::of('100', 'CAD');
$purchase_unit = new PurchaseUnit($amount);

$purchase_unit->addItem(Item::create('Item 1', '100.00', 'CAD', 2));
```

### PurchaseUnit::getItems()

Returns the items array of PurchaseUnit.

#### Signature

```php
public function getItems(): array;
```

#### Example

```php
$amount = AmountBreakdown::of('100', 'CAD');
$purchase_unit = new PurchaseUnit($amount);

$purchase_unit->addItem(Item::create('Item 1', '100.00', 'CAD', 2));
$purchase_unit->addItem(Item::create('Item 2', '100.00', 'CAD', 2));

$purchase_unit->getItems();
```
### PurchaseUnit::getAmount()

Returns the amount breakdown of PurchaseUnit.

#### Signature

```php
public function getAmount(): AmountBreakdown;
```

#### Example

```php
$amount = AmountBreakdown::of('100', 'CAD');
$purchase_unit = new PurchaseUnit($amount);

$amount = $purchaseUnit->getAmount();
```
