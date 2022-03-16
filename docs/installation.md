# Installation

The supported way of installing `paypal-checkout-sdk` package is via Composer.

```bash
composer require phpjuice/paypal-checkout-sdk
```

## Setup Credentials

Get client ID and client secret by going to [https://developer.paypal.com/developer/applications](https://developer.paypal.com/developer/applications) and generating a REST API app. Get Client ID and Secret from there.

## Setup a Paypal Client

In order to communicate with PayPal platform we need to set up a client first :

### Create a client with sandbox environment

```php
// import namespace
use PayPal\Http\Environment\SandboxEnvironment;
use PayPal\Http\PayPalClient;

// client id and client secret retrieved from PayPal
$clientId = "<<PAYPAL-CLIENT-ID>>";
$clientSecret = "<<PAYPAL-CLIENT-SECRET>>";

// create a new sandbox environment
$environment = new SandboxEnvironment($clientId, $clientSecret);

// create a new client
$client = new PayPalClient($environment);
```

### Create a client with production environment

```php
// import namespace
use PayPal\Http\Environment\ProductionEnvironment;
use PayPal\Http\PayPalClient;

// client id and client secret retrieved from PayPal
$clientId = "<<PAYPAL-CLIENT-ID>>";
$clientSecret = "<<PAYPAL-CLIENT-SECRET>>";

// create a new sandbox environment
$environment = new ProductionEnvironment($clientId, $clientSecret);

// create a new client
$client = new PayPalClient($environment);
```
