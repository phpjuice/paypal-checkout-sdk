<?php

namespace Tests\Orders;

use PHPUnit\Framework\TestCase;
use PayPal\Checkout\Orders\Item;
use PayPal\Checkout\Orders\Order;
use PayPal\Checkout\Orders\Amount;
use PayPal\Checkout\Orders\PurchaseUnit;
use PayPal\Checkout\Orders\ApplicationContext;
use PayPal\Checkout\Exceptions\OrderPurchaseUnitException;
use PayPal\Checkout\Exceptions\InvalidOrderIntentException;

class OrderTest extends TestCase
{
    public function testCreateOrderWithInvalidIntent()
    {
        $this->expectException(InvalidOrderIntentException::class);
        $this->expectExceptionMessage('Order intent provided is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#orders_create.');

        new Order('invalid intent');
    }

    public function testSetInvalidOrderIntent()
    {
        $this->expectException(InvalidOrderIntentException::class);
        $this->expectExceptionMessage('Order intent provided is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#orders_create.');

        $order = new Order();
        $order->setIntent('invalid intent');
    }

    public function testCreatesNewOrder()
    {
        $order = new Order();
        $this->assertEquals('CAPTURE', $order->getIntent());
        $this->assertEquals([
            'locale' => 'en-US',
            'shipping_preference' => 'NO_SHIPPING',
            'landing_page' => 'NO_PREFERENCE',
            'user_action' => 'CONTINUE',
        ], $order->getApplicationContext()->toArray());

        $order->setIntent('AUTHORIZE');
        $this->assertEquals('AUTHORIZE', $order->getIntent());
        $this->assertEmpty($order->getPurchaseUnits());
    }

    public function testAddPurchaseUnit()
    {
        $order = new Order();
        $this->assertEmpty($order->getPurchaseUnits());
        $order->addPurchaseUnit(new PurchaseUnit('USD', 200));
        $this->assertCount(1, $order->getPurchaseUnits());
    }

    public function testDoesNotAddMultiplePurchaseUnits()
    {
        $this->expectException(OrderPurchaseUnitException::class);
        $this->expectExceptionMessage('At present only 1 purchase_unit is supported.');

        $order = new Order();
        $order->addPurchaseUnit(new PurchaseUnit('USD', 200));
        $order->addPurchaseUnit(new PurchaseUnit('USD', 300));
    }

    public function testOrderHasOnePurchaseUnitAtLeast()
    {
        $this->expectException(OrderPurchaseUnitException::class);
        $this->expectExceptionMessage('Paypal orders must have 1 purchase_unit at least.');

        $order = new Order();
        $order->toArray();
    }

    public function testCreateAFullOrder()
    {
        $purchase_unit = new PurchaseUnit('USD', 300.00);
        $purchase_unit->addItem(new Item('Item 1', 'USD', 100.00, 1));
        $purchase_unit->addItem(new Item('Item 2', 'USD', 100.00, 2));
        $order = new Order('CAPTURE');
        $order->addPurchaseUnit($purchase_unit);
        $application_context = new ApplicationContext('Paypal Inc', 'en');
        $application_context->setUserAction('PAY_NOW');
        $application_context->setReturnUrl('https://site.com/payment/return');
        $application_context->setCancelUrl('https://site.com/payment/cancel');
        $order->setApplicationContext($application_context);
        $actual = $order->toArray();
        $expected = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => 300.00,
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => 'USD',
                                'value' => 300.00,
                            ],
                        ],
                    ],
                    'items' => [
                        [
                            'name' => 'Item 1',
                            'unit_amount' => [
                                'currency_code' => 'USD',
                                'value' => 100.00,
                            ],
                            'quantity' => 1,
                            'description' => '',
                            'category' => 'DIGITAL_GOODS',
                        ],
                        [
                            'name' => 'Item 2',
                            'unit_amount' => [
                                'currency_code' => 'USD',
                                'value' => 100.00,
                            ],
                            'quantity' => 2,
                            'description' => '',
                            'category' => 'DIGITAL_GOODS',
                        ],
                    ],
                ],
            ],
            'application_context' => [
                'brand_name' => 'Paypal Inc',
                'locale' => 'en',
                'shipping_preference' => 'NO_SHIPPING',
                'landing_page' => 'NO_PREFERENCE',
                'user_action' => 'PAY_NOW',
                'return_url' => 'https://site.com/payment/return',
                'cancel_url' => 'https://site.com/payment/cancel',
            ],
        ];
        $this->assertEquals($expected, $actual);
    }

    public function testCreateAFullOrderWithDiscount()
    {
        $purchase_unit = new PurchaseUnit('USD', 250.00);
        $purchase_unit->addItem(new Item('Item 1', 'USD', 100.00, 1));
        $purchase_unit->addItem(new Item('Item 2', 'USD', 100.00, 2));
        $amountBreakdown = $purchase_unit->getAmount();
        $amountBreakdown->setItemTotal(new Amount('USD', 300.00));
        $amountBreakdown->setDiscount(new Amount('USD', 50.00));

        $order = new Order('CAPTURE');
        $order->addPurchaseUnit($purchase_unit);
        $application_context = new ApplicationContext('Paypal Inc', 'en');
        $application_context->setUserAction('PAY_NOW');
        $application_context->setReturnUrl('https://site.com/payment/return');
        $application_context->setCancelUrl('https://site.com/payment/cancel');
        $order->setApplicationContext($application_context);
        $actual = $order->toArray();
        $expected = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => 250.00,
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => 'USD',
                                'value' => 300.00,
                            ],
                            'discount' => [
                                'currency_code' => 'USD',
                                'value' => 50.00,
                            ],
                        ],
                    ],
                    'items' => [
                        [
                            'name' => 'Item 1',
                            'unit_amount' => [
                                'currency_code' => 'USD',
                                'value' => 100.00,
                            ],
                            'quantity' => 1,
                            'description' => '',
                            'category' => 'DIGITAL_GOODS',
                        ],
                        [
                            'name' => 'Item 2',
                            'unit_amount' => [
                                'currency_code' => 'USD',
                                'value' => 100.00,
                            ],
                            'quantity' => 2,
                            'description' => '',
                            'category' => 'DIGITAL_GOODS',
                        ],
                    ],
                ],
            ],
            'application_context' => [
                'brand_name' => 'Paypal Inc',
                'locale' => 'en',
                'shipping_preference' => 'NO_SHIPPING',
                'landing_page' => 'NO_PREFERENCE',
                'user_action' => 'PAY_NOW',
                'return_url' => 'https://site.com/payment/return',
                'cancel_url' => 'https://site.com/payment/cancel',
            ],
        ];
        $this->assertEquals($expected, $actual);
    }

    public function testConvertOrderToJson()
    {
        $purchase_unit = new PurchaseUnit('USD', 70.00);
        $purchase_unit->addItem(new Item('Item 1', 'USD', 100.00, 1));
        $amountBreakdown = $purchase_unit->getAmount();
        $amountBreakdown->setItemTotal(new Amount('USD', 100.00));
        $amountBreakdown->setDiscount(new Amount('USD', 30.00));


        $order = new Order('CAPTURE');
        $order->addPurchaseUnit($purchase_unit);
        $application_context = new ApplicationContext('Paypal Inc', 'en-US');
        $application_context->setUserAction('PAY_NOW');
        $application_context->setReturnUrl('https://site.com/payment/return');
        $application_context->setCancelUrl('https://site.com/payment/cancel');
        $order->setApplicationContext($application_context);
        $expected = '{
            "intent": "CAPTURE",
            "purchase_units": [
                {
                    "amount": {
                        "currency_code": "USD",
                        "value": 70.00,
                        "breakdown": {
                            "item_total": {
                                "currency_code": "USD",
                                "value": 100.00
                            },
                            "discount": {
                                "currency_code": "USD",
                                "value": 30.00
                            }
                        }
                    },
                    "items": [
                        {
                            "name": "Item 1",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": 100.00
                            },
                            "quantity": 1,
                            "description": "",
                            "category": "DIGITAL_GOODS"
                        }
                    ]
                }
            ],
            "application_context": {
                "brand_name" : "Paypal Inc",
                "locale" : "en-US",
                "shipping_preference" : "NO_SHIPPING",
                "landing_page" : "NO_PREFERENCE",
                "user_action" : "PAY_NOW",
                "return_url" : "https://site.com/payment/return",
                "cancel_url" : "https://site.com/payment/cancel"
            }
        }';
        $actual = $order->toJson();
        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }
    public function testConvertOrderToJsonWithDiscount()
    {
        $purchase_unit = new PurchaseUnit('USD', 100.00);
        $purchase_unit->addItem(new Item('Item 1', 'USD', 100.00, 1));
        $order = new Order('CAPTURE');
        $order->addPurchaseUnit($purchase_unit);
        $application_context = new ApplicationContext('Paypal Inc', 'en-US');
        $application_context->setUserAction('PAY_NOW');
        $application_context->setReturnUrl('https://site.com/payment/return');
        $application_context->setCancelUrl('https://site.com/payment/cancel');
        $order->setApplicationContext($application_context);
        $expected = '{
            "intent": "CAPTURE",
            "purchase_units": [
                {
                    "amount": {
                        "currency_code": "USD",
                        "value": 100.00,
                        "breakdown": {
                            "item_total": {
                                "currency_code": "USD",
                                "value": 100.00
                            }
                        }
                    },
                    "items": [
                        {
                            "name": "Item 1",
                            "unit_amount": {
                                "currency_code": "USD",
                                "value": 100.00
                            },
                            "quantity": 1,
                            "description": "",
                            "category": "DIGITAL_GOODS"
                        }
                    ]
                }
            ],
            "application_context": {
                "brand_name" : "Paypal Inc",
                "locale" : "en-US",
                "shipping_preference" : "NO_SHIPPING",
                "landing_page" : "NO_PREFERENCE",
                "user_action" : "PAY_NOW",
                "return_url" : "https://site.com/payment/return",
                "cancel_url" : "https://site.com/payment/cancel"
            }
        }';
        $actual = $order->toJson();
        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }
}
