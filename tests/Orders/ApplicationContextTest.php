<?php

namespace Tests\Orders;

use PayPal\Checkout\Exceptions\InvalidLandingPageException;
use PayPal\Checkout\Exceptions\InvalidShippingPreferenceException;
use PayPal\Checkout\Exceptions\InvalidUserActionException;
use PayPal\Checkout\Orders\ApplicationContext;

it("throws an exception when setting invalid user action", function () {
    $applicationContext = new ApplicationContext();
    $applicationContext->setUserAction('invalid user action');
})->throws(
    InvalidUserActionException::class,
    // phpcs:ignore
    'User Action provided is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#definition-order_application_context.'
);

it("can set a valid user action", function () {
    $applicationContext = new ApplicationContext();
    $applicationContext->setUserAction('PAY_NOW');
    expect($applicationContext->getUserAction())->toBe('PAY_NOW');
    $applicationContext->setUserAction('CONTINUE');
    expect($applicationContext->getUserAction())->toBe('CONTINUE');
});

it("throws an exception when setting invalid shipping preferences", function () {
    $applicationContext = new ApplicationContext();
    $applicationContext->setShippingPreference('invalid shipping preference');
})->throws(
    InvalidShippingPreferenceException::class,
    // phpcs:ignore
    'Shipping preference provided is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#definition-order_application_context.'
);

it("can set shipping preferences", function () {
    $applicationContext = new ApplicationContext();
    expect($applicationContext->getShippingPreference())->toBe('NO_SHIPPING');
    $applicationContext->setShippingPreference('GET_FROM_FILE');
    expect($applicationContext->getShippingPreference())->toBe('GET_FROM_FILE');
    $applicationContext->setShippingPreference('NO_SHIPPING');
    expect($applicationContext->getShippingPreference())->toBe('NO_SHIPPING');
    $applicationContext->setShippingPreference('SET_PROVIDED_ADDRESS');
    expect($applicationContext->getShippingPreference())->toBe('SET_PROVIDED_ADDRESS');
});

it("throws an exception when setting invalid landing page", function () {
    $applicationContext = new ApplicationContext();
    $applicationContext->setLandingPage('invalid landing page');
})->throws(
    InvalidLandingPageException::class,
    // phpcs:ignore
    'Landing page provided is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#definition-order_application_context.' //
);

it("can set landing page", function () {
    $applicationContext = new ApplicationContext();
    expect($applicationContext->getLandingPage())->toBe('NO_PREFERENCE');
    $applicationContext->setLandingPage('LOGIN');
    expect($applicationContext->getLandingPage())->toBe('LOGIN');
    $applicationContext->setLandingPage('BILLING');
    expect($applicationContext->getLandingPage())->toBe('BILLING');
    $applicationContext->setLandingPage('NO_PREFERENCE');
    expect($applicationContext->getLandingPage())->toBe('NO_PREFERENCE');
});

it("can set locale", function () {
    // Act
    $applicationContext = new ApplicationContext();
    // Assert
    expect($applicationContext->getLocale())->toBe('en-US');

    // Act
    $applicationContext->setLocale('fr');
    // Assert
    expect($applicationContext->getLocale())->toBe('fr');
});

it("can set return and cancel urls", function () {
    // Act
    $applicationContext = new ApplicationContext();

    // Assert
    expect($applicationContext->getReturnUrl())->toBeNull();
    expect($applicationContext->getCancelUrl())->toBeNull();

    // Act
    $applicationContext->setReturnUrl('test return url');
    $applicationContext->setCancelUrl('test cancel url');

    // Assert
    expect($applicationContext->getReturnUrl())->toBe('test return url');
    expect($applicationContext->getCancelUrl())->toBe('test cancel url');
});


it("can cast to an array with no null values", function () {
    // Arrange
    $expected = [
        'locale' => 'en-US',
        'shipping_preference' => 'NO_SHIPPING',
        'landing_page' => 'NO_PREFERENCE',
        'user_action' => 'CONTINUE',
    ];

    // Act
    $applicationContext = new ApplicationContext();

    // Assert
    expect($applicationContext->toArray())->toBe($expected);
});

it("can cast to an array", function () {
    // Arrange
    $expected = [
        'brand_name' => 'Paypal Inc',
        'locale' => 'fr',
        'shipping_preference' => 'GET_FROM_FILE',
        'landing_page' => 'BILLING',
        'user_action' => 'PAY_NOW',
        'return_url' => 'https://site.com/payment/return',
        'cancel_url' => 'https://site.com/payment/cancel',
    ];

    // Act
    $applicationContext = new ApplicationContext('Paypal Inc');
    $applicationContext->setCancelUrl('https://site.com/payment/cancel');
    $applicationContext->setReturnUrl('https://site.com/payment/return');
    $applicationContext->setLocale('fr');
    $applicationContext->setShippingPreference('GET_FROM_FILE');
    $applicationContext->setLandingPage('BILLING');
    $applicationContext->setUserAction('PAY_NOW');

    // Assert
    expect($applicationContext->toArray())->toBe($expected);
});

it("can cast to json", function () {
    // Arrange
    $expected = json_encode([
        'brand_name' => 'Paypal Inc',
        'locale' => 'fr',
        'shipping_preference' => 'GET_FROM_FILE',
        'landing_page' => 'BILLING',
        'user_action' => 'PAY_NOW',
        'return_url' => 'https://site.com/payment/return',
        'cancel_url' => 'https://site.com/payment/cancel',
    ]);

    // Act
    $applicationContext = new ApplicationContext('Paypal Inc');
    $applicationContext->setCancelUrl('https://site.com/payment/cancel');
    $applicationContext->setReturnUrl('https://site.com/payment/return');
    $applicationContext->setLocale('fr');
    $applicationContext->setShippingPreference('GET_FROM_FILE');
    $applicationContext->setLandingPage('BILLING');
    $applicationContext->setUserAction('PAY_NOW');

    // Assert
    expect($applicationContext->toJson())->toBe($expected);
});
