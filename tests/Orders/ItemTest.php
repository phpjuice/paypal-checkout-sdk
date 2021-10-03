<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Orders;

use PayPal\Checkout\Exceptions\InvalidItemCategoryException;
use PayPal\Checkout\Orders\Amount;
use PayPal\Checkout\Orders\Item;

it("can initialize new item", function () {
    // Arrange
    $value = "100.00";
    $currency_code = "USD";
    $amount = new Amount($value, $currency_code);

    // Act
    $item = new Item('Item 1', $amount, 1);

    // Assert
    expect($item->getName())->toBe('Item 1');
    expect($item->getQuantity())->toBe(1);
});


it("can create new item", function () {
    // Arrange
    $value = "100.00";
    $currency_code = "USD";

    // Act
    $item = Item::create('Item 1', $value, $currency_code, 2);

    // Assert
    expect($item)->toBeInstanceOf(Item::class);
    expect($item->getName())->toBe('Item 1');
    expect($item->getQuantity())->toBe(2);
});

it("can cast to an array", function () {
    // Arrange
    $expected = [
        'name' => 'Item 1',
        'unit_amount' => [
            'currency_code' => 'CAD',
            'value' => '100.00',
        ],
        'quantity' => 2,
        'description' => 'Item Description',
        'category' => 'DIGITAL_GOODS',
    ];

    // Act
    $item = Item::create('Item 1', "100.00", "CAD");
    $item->setDescription('Item Description')
        ->setQuantity(2);

    // Assert
    expect($item->toArray())->toBe($expected);
});


it("can cast to json", function () {
    // Arrange
    $expected = json_encode([
        'name' => 'Item 1',
        'unit_amount' => [
            'currency_code' => 'CAD',
            'value' => '100.00',
        ],
        'quantity' => 2,
        'description' => 'Item Description',
        'category' => 'DIGITAL_GOODS',
    ]);

    // Act
    $item = Item::create('Item 1', "100.00", "CAD");
    $item->setDescription('Item Description')
        ->setQuantity(2);

    // Assert
    expect($item->toJson())->toBe($expected);
});

it("throws an exception when setting invalid category", function () {
    // Act
    $item = Item::create('Item', '100.00', 'CAD', 2);
    $item->setCategory('invalid category');
})->throws(
    InvalidItemCategoryException::class,
    'Item category is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#definition-item.'
);

it("returns item sku", function () {
    // Arrange
    $item = Item::create('Item', '100.00', 'CAD', 2);

    // Act
    $item->setSku('123456789');

    // Assert
    expect($item->getSku())->toBe('123456789');
});
