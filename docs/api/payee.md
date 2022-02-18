# Payee

See https://developer.paypal.com/docs/api/orders/v2/#payee.

## Methods

### Payee::__construct()

Creates a payee object using constructor.

#### Signature

```php
public function __construct(string $email_address, string $merchant_id);
```

#### Example

```php
$payee = new Payee('payee@paypal.com', 'YP568Y95AVSDY');
```

### Payee::create()

Creates a payee object.

#### Signature

```php
public static function create(string $email_address, string $merchant_id): Payee;
```

#### Example

```php
$payee = Payee::create('payee@paypal.com', 'YP568Y95AVSDY');
```

### Payee::setEmailAddress()

Sets an email address on a payee object.

#### Signature

```php
public function setEmailAddress(string $email_address): self
```

#### Example

```php
$payee = Payee::create('payee@paypal.com', 'YP568Y95AVSDY');
$payee->setEmailAddress('payee2@paypal.com');
```

### Payee::getEmailAddress()

Gets a payee email address.

#### Signature

```php
public function getEmailAddress(): string;
```

#### Example

```php
$payee = Payee::create('payee@paypal.com', 'YP568Y95AVSDY');
$payee->getEmailAddress() // payee@paypal.com;
```

### Payee::setMerchantId()

Sets a merchant id on a payee object.

#### Signature

```php
public function setMerchantId(string $merchant_id): self;
```

#### Example

```php
$payee = Payee::create('payee@paypal.com', 'YP568Y95AVSDY');
$payee->setEmailAddress('YP568Y95AVSDY');
```

### Payee::getMerchantId()

Gets a payee merchant id.

#### Signature

```php
public function getMerchantId(): ?string
```

#### Example

```php
$payee = Payee::create('payee@paypal.com', 'YP568Y95AVSDY');
$payee->getMerchantId() // YP568Y95AVSDY;
```
