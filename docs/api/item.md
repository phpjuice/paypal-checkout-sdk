# Item

See https://developer.paypal.com/docs/api/orders/v2/#definition-item.

## Methods

### Item::__construct()

Creates an item object using constructor.

#### Signature

```php
public function __construct(string $name, AmountContract $amount, int $quantity = 1);
```

#### Example

```php
$amount = Amount::of('100', 'USD');
$item = new Item('Item name', $amount, 2);
```

### Item::create()

A static helper method to create a new item.

#### Signature

```php
public static function create(string $name, string $value, string $currency_code = 'USD', int $quantity = 1): Item;
```

#### Example

```php
$item = Item::create('Item name', '100.00', 'USD', 4);
```

### Item::setUnitAmount()

A method to override a unit amount on an item.

#### Signature

```php
public function setUnitAmount(AmountContract $unit_amount): self;
```

#### Example

```php
$item = Item::create('Item name', '100.00', 'USD', 4);
$new_amount = Amount::of('200.00', 'USD');
$item->setUnitAmount($new_amount);
```

### Item::getSku()

Returns a sku value assigned to an Item, default to `uniqid()`.

#### Signature

```php
public function getSku(): string;
```

#### Example

```php
$item = Item::create('Item name', '100.00', 'USD', 4);
$item->getSku() // random ex:5819f3ad1c0ce
```

### Item::setSku()

Sets an sku value on an item.

#### Signature

```php
public function setSku(string $sku): self;
```

#### Example

```php
$item = Item::create('Item 1', '100.00', 'USD', 4);
$item->setSku('item_5819f3ad1c0ce');
$item->getSku(); // item_5819f3ad1c0ce
```

### Item::getName()

Returns an item name.

#### Signature

```php
public function getName(): ?string;
```

#### Example

```php
$item = Item::create('Item name', '100.00', 'USD', 4);
$item->getName() // Item name
```

### Item::setName()

Sets an item name.

#### Signature

```php
public function setName(string $name): self;
```

#### Example

```php
$item = Item::create('Item 1', '100.00', 'USD', 4);
$item->setName('My Item');
$item->getName(); // My Item
```

### Item::getDescription()

Returns an item description. defaults to `null`

#### Signature

```php
public function getDescription(): ?string;
```

#### Example

```php
$item = Item::create('Item name', '100.00', 'USD', 4);
$item->getDescription() // null
```

### Item::setDescription()

Sets an item description.

#### Signature

```php
public function setDescription(string $description): self;
```

#### Example

```php
$item = Item::create('Item 1', '100.00', 'USD', 4);
$item->setDescription('My Item description');
$item->getDescription(); // My Item description
```

### Item::getQuantity()

Returns an item quantity. defaults to `1`

#### Signature

```php
public function getQuantity(): int;
```

#### Example

```php
$item = Item::create('Item name', '100.00', 'USD', 4);
$item->getQuantity() // 4
```

### Item::setQuantity()

Sets an item quantity.

#### Signature

```php
public function setQuantity(int $quantity): self;
```

#### Example

```php
$item = Item::create('Item 1', '100.00', 'USD', 4);
$item->setQuantity(3);
$item->getQuantity(); // 3
```

### Item::getCategory()

Returns an item quantity. defaults to `DIGITAL_GOODS`

#### Signature

```php
public function getCategory(): ?string;
```

#### Example

```php
$item = Item::create('Item name', '100.00', 'USD', 4);
$item->getCategory() // DIGITAL_GOODS
```

### Item::setCategory()

Sets an item category.

#### Signature

```php
public function setCategory(string $category): self;
```

**Available category options**

```php
const DIGITAL_GOODS = 'DIGITAL_GOODS';
const PHYSICAL_GOODS = 'PHYSICAL_GOODS';
```

#### Example

```php
$item = Item::create('Item 1', '100.00', 'USD', 4);
$item->setCategory(PHYSICAL_GOODS);
$item->getCategory(); // PHYSICAL_GOODS
```

### Item::toArray()

Returns an item array representation. used when serializing an item into http request.

#### Signature

```php
public function toArray(): array;
```

#### Example

```php
$item = Item::create('Item name', '100.00', 'USD', 4);
$item->toArray();
// result
[
    'name' => 'Item name',
    'unit_amount' => [
        'value' => '100.00',
        'currency_code' => 'USD'
    ],
    'quantity' => 4,
    'description' => null,
    'category' => 'DIGITAL_GOODS',
]
```
