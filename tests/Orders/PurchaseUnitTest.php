<?php

namespace Tests\Orders;

use PayPal\Checkout\Exceptions\ItemTotalMismatchException;
use PayPal\Checkout\Exceptions\MultiCurrencyOrderException;
use PayPal\Checkout\Orders\Item;
use PayPal\Checkout\Orders\PurchaseUnit;
use PHPUnit\Framework\TestCase;

class PurchaseUnitTest extends TestCase
{
    public function testCreatePurchaseUnit()
    {
        $purchase_unit = new PurchaseUnit('USD', 100.00);
        $this->assertEquals('USD', $purchase_unit->getAmount()->getCurrencyCode());
        $this->assertEquals(100.00, $purchase_unit->getAmount()->getValue());
    }

    public function testAddItemToPurchaseUnit()
    {
        $purchase_unit = new PurchaseUnit('CAD', 100.00);
        $this->assertEmpty($purchase_unit->getItems());

        $item1 = new Item('item 1', 'CAD', 100.00, 1);
        $item2 = new Item('item 2', 'CAD', 100.00, 1);
        $purchase_unit->addItem($item1)
            ->addItem($item2);
        $this->assertEquals(2, count($purchase_unit->getItems()));
    }

    public function testThrowsMultiCurrencyException()
    {
        $this->expectException(MultiCurrencyOrderException::class);
        $this->expectExceptionMessage('Multiple differing values of currency_code are not supported. Entire Order request must have the same currency_code.');
        $purchase_unit = new PurchaseUnit('USD', 100.00);
        $item1 = new Item('item 1', 'USD', 100.00, 1);
        $item2 = new Item('item 2', 'USD', 100.00, 1);
        $item3 = new Item('item 3', 'CAD', 100.00, 1);
        $purchase_unit->addItem($item1)
            ->addItem($item2)
            ->addItem($item3);
    }

    public function testGetCalculatedAmount()
    {
        $purchase_unit = new PurchaseUnit('USD', 100.00);
        $item1 = new Item('item 1', 'USD', 100.00, 1);
        $item2 = new Item('item 2', 'USD', 100.00, 2);
        $item3 = new Item('item 3', 'USD', 50.00, 4);
        $purchase_unit->addItem($item1)
            ->addItem($item2)
            ->addItem($item3);
        $this->assertEquals(500, $purchase_unit->getCalculatedAmount());
    }

    public function testThrowsItemTotalMismatchException()
    {
        $this->expectException(ItemTotalMismatchException::class);
        $this->expectExceptionMessage('Items Total Should equal sum of (unit_amount * quantity) across all items for a given purchase_unit');
        $pu = new PurchaseUnit('USD', 100);
        $item1 = new Item('item 1', 'USD', 49.66, 1);
        $item2 = new Item('item 2', 'USD', 50.33, 1);
        $pu->addItem($item1);
        $pu->addItem($item2);
        $pu->toArray();
    }

    public function testCalculatedAmountMatchesAmount()
    {
        $pu = new PurchaseUnit('USD', 100);
        $item1 = new Item('item 1', 'USD', 49.67, 1);
        $item2 = new Item('item 2', 'USD', 50.33, 1);
        $pu->addItem($item1);
        $pu->addItem($item2);
        $this->assertEquals(100, $pu->getCalculatedAmount());
    }

    public function testToArray()
    {
        $purchase_unit = new PurchaseUnit('USD', 300.00);
        $item1 = new Item('item 1', 'USD', 100.00, 1);
        $item1->setDescription('item 1 description');
        $item2 = new Item('item 2', 'USD', 100.00, 2);
        $purchase_unit->addItem($item1)
            ->addItem($item2);
        $actual = $purchase_unit->toArray();
        $expected = [
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
                    'name' => 'item 1',
                    'unit_amount' => [
                        'currency_code' => 'USD',
                        'value' => 100.00,
                    ],
                    'quantity' => 1,
                    'description' => 'item 1 description',
                    'category' => 'DIGITAL_GOODS',
                ],
                [
                    'name' => 'item 2',
                    'unit_amount' => [
                        'currency_code' => 'USD',
                        'value' => 100.00,
                    ],
                    'quantity' => 2,
                    'description' => '',
                    'category' => 'DIGITAL_GOODS',
                ],
            ],
        ];
        $this->assertEquals($expected, $actual);
    }

    public function testToJson()
    {
        $purchase_unit = new PurchaseUnit('USD', 300.00);
        $item1 = new Item('item 1', 'USD', 100.00, 1);
        $item2 = new Item('item 2', 'USD', 100.00, 2);
        $purchase_unit->addItem($item1)
            ->addItem($item2);
        $actual = $purchase_unit->toJson();
        $expected = '{
            "amount": {
                "currency_code": "USD",
                "value": 300.00,
                "breakdown": {
                    "item_total": {
                        "currency_code": "USD",
                        "value": 300.00
                    }
                }
            },
            "items": [
                {
                    "name": "item 1",
                    "unit_amount": {
                        "currency_code": "USD",
                        "value": 100.00
                    },
                    "quantity": 1,
                    "description": "",
                    "category": "DIGITAL_GOODS"
                },
                {
                    "name": "item 2",
                    "unit_amount": {
                        "currency_code": "USD",
                        "value": 100.00
                    },
                    "quantity": 2,
                    "description": "",
                    "category": "DIGITAL_GOODS"
                }
            ]
        }';
        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }
}
