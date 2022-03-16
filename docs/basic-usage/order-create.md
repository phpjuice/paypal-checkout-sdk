# Create an Order

An order represents a payment between two or more parties. and inorder to create a new order we must send an `OrderCreateRequest` to PayPal API.

```php
use PayPal\Checkout\Requests\OrderCreateRequest;
use PayPal\Checkout\Orders\AmountBreakdown;
use PayPal\Checkout\Orders\Item;
use PayPal\Checkout\Orders\Order;
use PayPal\Checkout\Orders\PurchaseUnit;

// Create a purchase unit with the total amount
$purchase_unit = new PurchaseUnit(AmountBreakdown::of('200.00', 'USD'));

// Create & add item to purchase unit
$purchase_unit->addItem(Item::make('Item 1', '100.00', 'USD', 1));
$purchase_unit->addItem(Item::make('Item 2', '100.00', 'USD', 1));

// Create a new order with intent to capture a payment
$order = (new Order())->addPurchaseUnit($purchase_unit);

// Send request to PayPal
$response = $client->send(new OrderCreateRequest($order));

// Get results
$result = json_decode($response->getBody()->getContents());
```

A successful request returns the HTTP `201` Created status code and a JSON response body that includes by default a minimal response with the `ID`, `status`, and `HATEOAS` links.

```json
{
    "id": "8F783829JA718493L",
    "status": "CREATED",
    "links": [
        {
            "href": "https://api.sandbox.paypal.com/v2/checkout/orders/8F783829JA718493L",
            "rel": "self",
            "method": "GET"
        },
        {
            "href": "https://www.sandbox.paypal.com/checkoutnow?token=8F783829JA718493L",
            "rel": "approve",
            "method": "GET"
        },
        {
            "href": "https://api.sandbox.paypal.com/v2/checkout/orders/8F783829JA718493L",
            "rel": "update",
            "method": "PATCH"
        },
        {
            "href": "https://api.sandbox.paypal.com/v2/checkout/orders/8F783829JA718493L/capture",
            "rel": "capture",
            "method": "POST"
        }
    ]
}
```
