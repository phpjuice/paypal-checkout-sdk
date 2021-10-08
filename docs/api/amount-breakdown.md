# Amount Breakdown

## Methods

### AmountBreakdown::__construct()

Creates an amount object using constructor.

```php
$amount = new AmountBreakdown('100.00', 'USD');
```

**Signature**

```php
public function __construct(string $value, string $currency_code = 'USD');
```

### AmountBreakdown::of()

Creates an amount from a value and an optional currency code.

```php
$amount = AmountBreakdown::of('100.00', 'USD');
```

**Signature**

```php
public static function of(string $value, string $currency_code = 'USD'): AmountBreakdown;
```

### AmountBreakdown::getCurrencyCode()

Gets an amount currency code.

```php
$amount = AmountBreakdown::of('100.00', 'USD');
$currency = $amount->getCurrencyCode(); // USD
```

**Signature**

```php
public function getCurrencyCode(): string;
```

### AmountBreakdown::getValue()

Gets an amount value.

```php
$amount = AmountBreakdown::of('100.00', 'USD');
$value = $amount->getValue(); // '100.00'
```

**Signature**

```php
public function getValue(): string;
```

### AmountBreakdown::toArray()

Casts the AmountBreakdown an array representation, used when serializing an amount into http request.

```php
$amount = AmountBreakdown::of('100.00', 'USD');
$array = $amount->toArray(); // ['value' => '100.00', 'currency_code' => 'USD']
```

**Signature**

```php
public function toArray(): array;
```
