<?php

namespace Tests\Orders;

use PayPal\Checkout\Exceptions\InvalidItemCategoryException;
use PayPal\Checkout\Orders\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testToArray()
    {
        $expected = [
            'name' => 'booking',
            'unit_amount' => [
                'currency_code' => 'USD',
                'value' => 100.00,
            ],
            'quantity' => 2,
            'description' => 'this is my test description',
            'category' => 'DIGITAL_GOODS',
        ];

        $item = new Item('booking', 'USD', 100.00, 1);
        $item->setDescription('this is my test description')
            ->setQuantity(2);
        $this->assertEquals($expected, $item->toArray());
    }

    public function testToJson()
    {
        $expected = '{
            "name": "booking",
            "unit_amount": {
                "currency_code": "USD",
                "value": 100.00
            },
            "quantity": 2,
            "description": "this is my test description",
            "category": "DIGITAL_GOODS"
        }';

        $item = new Item('booking', 'USD', 100.00, 1);
        $item->setDescription('this is my test description')
            ->setQuantity(2);
        $this->assertJsonStringEqualsJsonString($expected, $item->toJson());
    }

    public function testSetInvalidItemCategory()
    {
        $this->expectException(InvalidItemCategoryException::class);
        $this->expectExceptionMessage('Item category is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#definition-item.');

        $item = new Item('booking', 'USD', 100.00, 1);
        $item->setCategory('invalid category');
    }
}
