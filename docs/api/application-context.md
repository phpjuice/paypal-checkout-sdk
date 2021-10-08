# ApplicationContext

See https://developer.paypal.com/docs/api/orders/v2/#definition-order_application_context

## Methods

### ApplicationContext::__construct()

Creates an application context object using constructor.

#### Signature

```php
public function __construct(
    ?string $brand_name = null,
    ?string $locale = 'en-US',
    ?string $landing_page = NO_PREFERENCE,
    ?string $shipping_preference = NO_SHIPPING,
    ?string $return_url = null,
    ?string $cancel_url = null
);
```

#### Example

```php
$application_context = new ApplicationContext('PHPJuice', 'en-US');
```

### ApplicationContext::create()

Helper method to create an `ApplicationContext` statically.

#### Signature

```php
public static function create(
    ?string $brand_name = null,
    ?string $locale = 'en-US',
    ?string $landing_page = NO_PREFERENCE,
    ?string $shipping_preference = NO_SHIPPING,
    ?string $return_url = null,
    ?string $cancel_url = null
): ApplicationContext;
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US');
```

### ApplicationContext::getBrandName()

Returns the brand name value from the application context.

#### Signature

```php
public function getBrandName(): ?string;
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US');
$application_context->getBrandName() // PHPJuice
```

### ApplicationContext::setBrandName()

Sets the brand name value on an application context.

#### Signature

```php
public function setBrandName($brand_name): self;
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US');
$application_context->setBrandName('PHPJuice Inc');
$application_context->getBrandName(); // PHPJuice Inc
```

### ApplicationContext::getLocale()

Returns the locale value from the application context, default to `en-US`.

#### Signature

```php
public function getLocale(): string;
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US');
$application_context->getLocale() // en-US
```

### ApplicationContext::setLocale()

Sets a locale on an application context.

#### Signature

```php
public function setLocale($locale): self;
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US');
$application_context->setLocale('en-GB');
$application_context->getLocale(); // en-GB
```

### ApplicationContext::getShippingPreference()

Returns the shipping preference value from an application context, default to `NO_SHIPPING`.

#### Signature

```php
public function getShippingPreference(): string;
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US', 'NO_SHIPPING');
$application_context->getShippingPreference() // NO_SHIPPING
```

### ApplicationContext::setShippingPreference()

Sets a shipping preference value on an application context.

#### Signature

```php
public function setShippingPreference($shipping_preference): self;
```

**Available shipping preference options**

```php
const GET_FROM_FILE = 'GET_FROM_FILE';
const NO_SHIPPING = 'NO_SHIPPING';
const SET_PROVIDED_ADDRESS = 'SET_PROVIDED_ADDRESS';
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US');
$application_context->setShippingPreference('GET_FROM_FILE');
$application_context->getShippingPreference(); // GET_FROM_FILE
```

### ApplicationContext::getLandingPage()

Returns the landing page value from an application context, default to `NO_PREFERENCE`.

#### Signature

```php
public function getLandingPage(): string;
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US');
$application_context->getLandingPage() // NO_PREFERENCE
```

### ApplicationContext::setLandingPage()

Sets a landing page value on an application context.

#### Signature

```php
public function setLandingPage($landing_page): self;
```

**Available landing page options**

```php
const LOGIN = 'LOGIN';
const BILLING = 'BILLING';
const NO_PREFERENCE = 'NO_PREFERENCE';
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US');
$application_context->setLandingPage('BILLING');
$application_context->getLandingPage(); // BILLING
```

### ApplicationContext::getUserAction()

Returns the user action value from an application context, default to `CONTINUE`.

#### Signature

```php
public function getUserAction(): string;
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US');
$application_context->getUserAction() // CONTINUE
```

### ApplicationContext::setUserAction()

Sets a user action value on an application context.

#### Signature

```php
public function setUserAction($user_action): self;
```

**Available user action options**

```php
const ACTION_CONTINUE = 'CONTINUE';
const ACTION_PAY_NOW = 'PAY_NOW';
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US');
$application_context->setUserAction('PAY_NOW');
$application_context->getUserAction(); // PAY_NOW
```

### ApplicationContext::getReturnUrl()

Returns the return url value from an application context, default to `null`.

#### Signature

```php
public function getReturnUrl(): string;
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US');
$application_context->getReturnUrl() // null
```

### ApplicationContext::setReturnUrl()

Sets a return url value on an application context.

#### Signature

```php
public function setReturnUrl($return_url): self;
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US');
$application_context->setReturnUrl('https://phpjuice.org/success');
$application_context->getReturnUrl(); // https://phpjuice.org/success
```

### ApplicationContext::getCancelUrl()

Returns the cancel url value from an application context, default to `null`.

#### Signature

```php
public function getCancelUrl(): string;
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US');
$application_context->getCancelUrl() // null
```

### ApplicationContext::setCancelUrl()

Sets a cancel url value on an application context.

#### Signature

```php
public function setCancelUrl($cancel_url): self;
```

#### Example

```php
$application_context = ApplicationContext::create('PHPJuice', 'en-US');
$application_context->setCancelUrl('https://phpjuice.org/cancel');
$application_context->getCancelUrl(); // https://phpjuice.org/cancel
```
