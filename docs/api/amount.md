# Amount

See https://developer.paypal.com/docs/api/orders/v2/#definition-Amount_breakdown.

## Methods

### Amount::__construct()

Creates an amount object using constructor.

#### Signature

```php
public function __construct(string $value, string $currency_code = 'USD');
```

#### Example

```php
$amount = new Amount('100.00', 'USD');
```

### Amount::of()

Creates an amount from a value and an optional currency code.

#### Signature

```php
public static function of(string $value, string $currency_code = 'USD'): Amount;
```

#### Example

```php
$amount = Amount::of('100.00', 'USD');
```

### Amount::getCurrencyCode()

Gets an amount currency code.

#### Signature

```php
public function getCurrencyCode(): string;
```

#### Example

```php
$amount = Amount::of('100.00', 'USD');
$currency = $amount->getCurrencyCode(); // USD
```

### Amount::getValue()

Gets an amount value.

#### Signature

```php
public function getValue(): string;
```

#### Example

```php
$amount = Amount::of('100.00', 'USD');
$value = $amount->getValue(); // '100.00'
```

### Amount::toArray()

Casts the Amount an array representation, used when serializing an amount into http request.

#### Signature

```php
public function toArray(): array;
```

#### Example

```php
$amount = Amount::of('100.00', 'USD');
$array = $amount->toArray(); 
// result
[   
    'value' => '100.00', 
    'currency_code' => 'USD'
];
```
