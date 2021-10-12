# Show an Order

Inorder to show an order we must send an `OrderShowRequest` to PayPal API with `order_id` in question.

```php
use PayPal\Checkout\Requests\OrderShowRequest;

// Get order id from database or request
$order_id = '8F783829JA718493L';

// Create an order show http request
$request = new OrderShowRequest($order_id);

// Send request to PayPal
$response = $client->send($request);

// Get results
$result = json_decode($response->getBody()->getContents());
```

A successful request returns the HTTP `200` status code and a JSON response body that includes by default a minimal response with the `ID`, `status`, and `HATEOAS` links.

```javascript
{
    "id": "8F783829JA718493L",
    "intent": "CAPTURE",
    "status": "CREATED",
    "purchase_units": [
        {
            "reference_id": "default",
            "amount": {
                "currency_code": "USD",
                "value": "100.00",
                "breakdown": {
                    "item_total": {
                        "currency_code": "USD",
                        "value": "100.00"
                    }
                }
            },
            "payee": {
                "email_address": "merchant@phpjuice.com",
                "merchant_id": "ZCM7386XH4H6Q"
            },
            "items": [
                {
                    "name": "Item 1",
                    "unit_amount": {
                        "currency_code": "USD",
                        "value": "100.00"
                    },
                    "quantity": "1",
                    "description": "My item description",
                    "category": "DIGITAL_GOODS"
                }
            ]
        }
    ],
    "create_time": "2021-10-04T13:21:27Z",
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

