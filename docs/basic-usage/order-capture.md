# Capture an Order

To successfully capture payment for an order, the buyer must first approve the order or a valid payment_source must be
provided in the request. A buyer can approve the order upon being redirected to the `rel:
approve` URL that was provided the HATEOAS links in the [Create Order](order-create.md) response.

```php
use PayPal\Checkout\Http\OrderCaptureRequest;

// Get order id from database or request
$order_id = '8F783829JA718493L';

// Create an order show http request
$request = new OrderCaptureRequest($order_id);

// Send request to PayPal
$response = $client->send($request);
```

A successful response to a non-idempotent request returns the HTTP `201` Created status code with a JSON response body
that shows captured payment details. If a duplicate response is retried, returns the HTTP `200` OK status code. By
default, the response is minimal.

```json
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

If a payment is not yet approved by the buyer, An error response with status code `422` is returned with a JSON response
body that shows errors.

```json
{
    "name": "UNPROCESSABLE_ENTITY",
    "details": [
        {
            "issue": "ORDER_NOT_APPROVED",
            "description": "Payer has not yet approved the Order for payment. Please redirect the payer to the 'rel':'approve' url returned as part of the HATEOAS links within the Create Order call or provide a valid payment_source in the request."
        }
    ],
    "message": "The requested action could not be performed, semantically incorrect, or failed business validation.",
    "debug_id": "80cb04a5dbea7",
    "links": [
        {
            "href": "https://developer.paypal.com/docs/api/orders/v2/#error-ORDER_NOT_APPROVED",
            "rel": "information_link",
            "method": "GET"
        }
    ]
}
```

## Catching Errors from PayPal

Inorder to catch validation errors from PayPal, you can add the following:

```php
use GuzzleHttp\Exception\GuzzleException;

try {
    $id = '8F783829JA718493L';
    $response = $client->send(new OrderCaptureRequest($id));
} catch (GuzzleException $e) {
    $r = $e->getResponse();
    $response = json_decode((string) $r->getBody());
}
```
