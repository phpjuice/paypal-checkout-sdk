<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Orders;

use PayPal\Checkout\Exceptions\MultiCurrencyOrderException;
use PayPal\Checkout\Orders\Amount;
use PayPal\Checkout\Orders\AmountBreakdown;
use PayPal\Checkout\Orders\Item;
use PayPal\Checkout\Orders\PurchaseUnit;

it("can initialize a purchase unit", function () {
    // Arrange
    $amount = AmountBreakdown::of('100.00', 'CAD');

    // Act
    $purchase_unit = new PurchaseUnit($amount);

    // Assert
    expect($purchase_unit->getAmount()->getCurrencyCode())->toBe('CAD');
    expect($purchase_unit->getAmount()->getValue())->toBe('100.00');
});


it("can create an empty purchase unit", function () {
    // Arrange
    $amount = AmountBreakdown::of('100.00', 'CAD');

    // Act
    $purchase_unit = new PurchaseUnit($amount);

    // Assert
    expect(count($purchase_unit->getItems()))->toBe(0);
});


it("can add a single item", function () {
    // Arrange
    $amount = AmountBreakdown::of('100', 'CAD');
    $purchase_unit = new PurchaseUnit($amount);

    // Act
    $purchase_unit->addItem(Item::create('Item 1', '100.00', 'CAD', 2));

    // Assert
    expect(count($purchase_unit->getItems()))->toBe(1);
});


it("can add multiple items", function () {
    // Arrange
    $amount = AmountBreakdown::of('100', 'CAD');
    $purchase_unit = new PurchaseUnit($amount);
    $items = array_map(function ($index) {
        return Item::create("Item $index", '100.00', 'CAD', $index);
    }, [1, 2, 3]);

    // Act
    $purchase_unit->addItems($items);

    // Assert
    expect(count($purchase_unit->getItems()))->toBe(3);
});

it("it throws an exception when using multiple currencies", function () {
    $amount = AmountBreakdown::of('100', 'CAD');
    $purchase_unit = new PurchaseUnit($amount);

    // Act
    $item = Item::create('Item', '100', 'EUR', 2);

    $purchase_unit->addItem($item);
})->throws(MultiCurrencyOrderException::class);


it("casts to an array", function () {
    // Arrange
    $amount = AmountBreakdown::of('300.00', 'CAD');
    $purchase_unit = new PurchaseUnit($amount);

    // Act
    $item_amount = Amount::of('100.00', 'CAD');
    $item1 = new Item('Item 1', $item_amount, 1);
    $item2 = new Item('Item 2', $item_amount, 2);
    $purchase_unit->addItem($item1)->addItem($item2);

    // Assert
    expect($purchase_unit->toArray())->toBe([
        'amount' => [
            'currency_code' => 'CAD',
            'value' => '300.00',
            'breakdown' => [
                'item_total' => [
                    'currency_code' => 'CAD',
                    'value' => '300.00',
                ],
            ],
        ],
        'items' => [
            [
                'name' => 'Item 1',
                'unit_amount' => [
                    'currency_code' => 'CAD',
                    'value' => '100.00',
                ],
                'quantity' => 1,
                'description' => '',
                'category' => 'DIGITAL_GOODS',
            ],
            [
                'name' => 'Item 2',
                'unit_amount' => [
                    'currency_code' => 'CAD',
                    'value' => '100.00',
                ],
                'quantity' => 2,
                'description' => '',
                'category' => 'DIGITAL_GOODS',
            ],
        ],
    ]);
});
