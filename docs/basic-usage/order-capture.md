# Capture an Order

To successfully capture payment for an order, the buyer must first approve the order or a valid payment\_source must be provided in the request. A buyer can approve the order upon being redirected to the `rel: approve` URL that was provided the HATEOAS links in the [Create Order](order-create.md) response.

```php
use PayPal\Checkout\Requests\OrderCaptureRequest;

// Get order id from database or request
$order_id = '8F783829JA718493L';

// Create an order show http request
$request = new OrderCaptureRequest($order_id);

// Send request to PayPal
$response = $client->send($request);

// Get results
$result = json_decode($response->getBody()->getContents());
```

A successful response to a non-idempotent request returns the HTTP `201` Created status code with a JSON response body that shows captured payment details. If a duplicate response is retried, returns the HTTP `200` OK status code. By default, the response is minimal.

```json
{
    "id": "8F783829JA718493L",
    "intent": "CAPTURE",
    "status": "COMPLETED",
    "purchase_units": [
        {
            "reference_id": "default",
            "amount": {
                "currency_code": "CAD",
                "value": "200.00",
                "breakdown": {
                    "item_total": {
                        "currency_code": "CAD",
                        "value": "200.00"
                    },
                    "shipping": {
                        "currency_code": "CAD",
                        "value": "0.00"
                    },
                    "handling": {
                        "currency_code": "CAD",
                        "value": "0.00"
                    },
                    "insurance": {
                        "currency_code": "CAD",
                        "value": "0.00"
                    },
                    "shipping_discount": {
                        "currency_code": "CAD",
                        "value": "0.00"
                    }
                }
            },
            "payee": {
                "email_address": "merchant@phpjuice.com",
                "merchant_id": "ZCM7386XH4H6Q"
            },
            "description": "My item description",
            "items": [
                {
                    "name": "Item",
                    "unit_amount": {
                        "currency_code": "CAD",
                        "value": "100.00"
                    },
                    "tax": {
                        "currency_code": "CAD",
                        "value": "0.00"
                    },
                    "quantity": "2"
                }
            ],
            "payments": {
                "captures": [
                    {
                        "id": "6SW93058HS0959910",
                        "status": "COMPLETED",
                        "amount": {
                            "currency_code": "CAD",
                            "value": "200.00"
                        },
                        "final_capture": true,
                        "seller_protection": {
                            "status": "ELIGIBLE",
                            "dispute_categories": [
                                "ITEM_NOT_RECEIVED",
                                "UNAUTHORIZED_TRANSACTION"
                            ]
                        },
                        "seller_receivable_breakdown": {
                            "gross_amount": {
                                "currency_code": "CAD",
                                "value": "200.00"
                            },
                            "paypal_fee": {
                                "currency_code": "CAD",
                                "value": "6.10"
                            },
                            "net_amount": {
                                "currency_code": "CAD",
                                "value": "193.90"
                            }
                        },
                        "links": [
                            {
                                "href": "https:\/\/api.sandbox.paypal.com\/v2\/payments\/captures\/6SW93058HS0959910",
                                "rel": "self",
                                "method": "GET"
                            },
                            {
                                "href": "https:\/\/api.sandbox.paypal.com\/v2\/payments\/captures\/6SW93058HS0959910\/refund",
                                "rel": "refund",
                                "method": "POST"
                            },
                            {
                                "href": "https:\/\/api.sandbox.paypal.com\/v2\/checkout\/orders\/8F783829JA718493L",
                                "rel": "up",
                                "method": "GET"
                            }
                        ],
                        "create_time": "2021-10-04T16:19:27Z",
                        "update_time": "2021-10-04T16:19:27Z"
                    }
                ]
            }
        }
    ],
    "payer": {
        "name": {
            "given_name": "John",
            "surname": "Doe"
        },
        "email_address": "buyer@phpjuice.com",
        "payer_id": "DCTWHLD9BMMMC",
        "address": {
            "country_code": "CA"
        }
    },
    "create_time": "2021-10-04T13:21:27Z",
    "update_time": "2021-10-04T16:19:27Z",
    "links": [
        {
            "href": "https:\/\/api.sandbox.paypal.com\/v2\/checkout\/orders\/8F783829JA718493L",
            "rel": "self",
            "method": "GET"
        }
    ]
}
```

## Catching Errors

If a payment is not yet approved by the buyer, An error response with status code `422` is returned with a JSON response body that shows errors.

Inorder to catch validation errors from PayPal, you can add the following:

```php
use GuzzleHttp\Exception\RequestException;

try {
    $id = '8F783829JA718493L';
    $response = $client->send(new OrderCaptureRequest($id));
    $result = json_decode($response->getBody()->getContents());
} catch (RequestException $e) {
    $errors = json_decode($e->getResponse()->getBody()->getContents());
}
```

Errors :

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

