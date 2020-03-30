# PayPal Checkout SDK


[![GitHub](https://img.shields.io/github/license/phpjuice/paypal-checkout-sdk.svg?style=flat-square)](https://github.com/phpjuice/paypal-checkout-sdk/blob/master/LICENSE)

PayPal Checkout SDK is a wrapper around the V2 Paypal rest API.

## Installation

PayPal Checkout SDK Package requires PHP 7.2 or higher.

> **INFO:** If you are using an older version of php this package may not function correctly.

The supported way of installing PayPal Checkout SDK package is via Composer.

```bash
composer require phpjuice/paypal-checkout-sdk
```

## Usage

PayPal Checkout SDK is designed to simplify using the new paypal checkout api in your app.


### Setup Credentials

Get client ID and client secret by going to [https://developer.paypal.com/developer/applications](https://developer.paypal.com/developer/applications) and generating a REST API app. Get Client ID and Secret from there.

### Setup a Paypal Client

Inorder to communicate with paypal platform we need to setup a client first :

- Create a client with sandbox environment
```php
// import namespace
use PayPal\Checkout\Environment\SandboxEnvironment;
use PayPal\Checkout\Http\PayPalClient;

// client id and client secret retrieved from paypal
$clientId = "<<PAYPAL-CLIENT-ID>>";
$clientSecret = "<<PAYPAL-CLIENT-SECRET>>";

// create a new sandbox environment
$environment = new SandboxEnvironment($clientId, $clientSecret);

// create a new client
$client = new PayPalClient($environment);
```

- Create a client with production environment
```php
// import namespace
use PayPal\Checkout\Environment\Production;
use PayPal\Checkout\Http\PayPalClient;

// client id and client secret retrieved from paypal
$clientId = "<<PAYPAL-CLIENT-ID>>";
$clientSecret = "<<PAYPAL-CLIENT-SECRET>>";

// create a new sandbox environment
$environment = new Production($clientId, $clientSecret);

// create a new client
$client = new PayPalClient($environment);
```


### Create a new Order


```php
// import namespace
use PayPal\Checkout\Http\OrderCreateRequest;
use PayPal\Checkout\Orders\Item;
use PayPal\Checkout\Orders\Order;
use PayPal\Checkout\Orders\PurchaseUnit;

// create a purchase unit with the total amount
$purchase_unit = new PurchaseUnit('USD', 100.00);
// create a new item
$item = new Item('Item 1', 'USD', 100.00, 1);
// add item to purchase unit
$purchase_unit->addItem($item);
// create a new order with intent to capture a payment
$order = new Order('CAPTURE');
// add a purchase unit to order
$order->addPurchaseUnit($purchase_unit);

// create an order create http request
$request = new OrderCreateRequest($order);
// send request to paypal
$response = $client->send($request);
// parse result
$result = json_decode((string) $response->getBody());
echo $result->id; // id of the created order
```


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todo list.

## Security

If you discover any security related issues, please email author instead of using the issue tracker.

## Credits

- [PayPal Docs](https://developer.paypal.com/docs/)

## License

license. Please see the [license file](LICENCE) for more information.

[![GitHub](https://img.shields.io/github/license/phpjuice/paypal-checkout-sdk.svg?style=flat-square)](https://github.com/phpjuice/paypal-checkout-sdk/blob/master/LICENSE)
