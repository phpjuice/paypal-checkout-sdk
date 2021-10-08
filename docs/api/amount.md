# Amount

## Methods

### Amount::__construct()

Creates an amount object using constructor.

```php
$amount = new Amount('100.00', 'USD');
```

**Signature**

```php
public function __construct(string $value, string $currency_code = 'USD');
```

### Amount::of()

Creates an amount from a value and an optional currency code.

```php
$amount = Amount::of('100.00', 'USD');
```

**Signature**

```php
public static function of(string $value, string $currency_code = 'USD'): Amount;
```

### Amount::getCurrencyCode()

Gets an amount currency code.

```php
$amount = Amount::of('100.00', 'USD');
$currency = $amount->getCurrencyCode(); // USD
```

**Signature**

```php
public function getCurrencyCode(): string;
```

### Amount::getValue()

Gets an amount value.

```php
$amount = Amount::of('100.00', 'USD');
$value = $amount->getValue(); // '100.00'
```

**Signature**

```php
public function getValue(): string;
```

### Amount::toArray()

Casts the Amount an array representation, used when serializing an amount into http request.

```php
$amount = Amount::of('100.00', 'USD');
$array = $amount->toArray(); // ['value' => '100.00', 'currency_code' => 'USD']
```

**Signature**

```php
public function toArray(): array;
```
