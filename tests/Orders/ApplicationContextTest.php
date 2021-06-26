<?php

namespace Tests\Orders;

use PayPal\Checkout\Exceptions\InvalidLandingPageException;
use PayPal\Checkout\Exceptions\InvalidShippingPreferenceException;
use PayPal\Checkout\Exceptions\InvalidUserActionException;
use PayPal\Checkout\Orders\ApplicationContext;
use PHPUnit\Framework\TestCase;

class ApplicationContextTest extends TestCase
{
    public function testSetInvalidUserAction()
    {
        $this->expectException(InvalidUserActionException::class);
        $this->expectExceptionMessage('User Action provided is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#definition-order_application_context.');

        $applicationContext = new ApplicationContext();
        $applicationContext->setUserAction('invalid user action');
    }

    public function testSetValidUserAction()
    {
        $applicationContext = new ApplicationContext();
        $applicationContext->setUserAction('PAY_NOW');
        $this->assertEquals('PAY_NOW', $applicationContext->getUserAction());
        $applicationContext->setUserAction('CONTINUE');
        $this->assertEquals('CONTINUE', $applicationContext->getUserAction());
    }

    public function testSetInvalidShippingPreferences()
    {
        $this->expectException(InvalidShippingPreferenceException::class);
        $this->expectExceptionMessage('Shipping preference provided is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#definition-order_application_context.');

        $applicationContext = new ApplicationContext();
        $applicationContext->setShippingPreference('invalid shipping preference');
    }

    public function testSetValidShippingPreferences()
    {
        $applicationContext = new ApplicationContext();
        $this->assertEquals('NO_SHIPPING', $applicationContext->getShippingPreference());
        $applicationContext->setShippingPreference('GET_FROM_FILE');
        $this->assertEquals('GET_FROM_FILE', $applicationContext->getShippingPreference());
        $applicationContext->setShippingPreference('NO_SHIPPING');
        $this->assertEquals('NO_SHIPPING', $applicationContext->getShippingPreference());
        $applicationContext->setShippingPreference('SET_PROVIDED_ADDRESS');
        $this->assertEquals('SET_PROVIDED_ADDRESS', $applicationContext->getShippingPreference());
    }

    public function testSetInvalidLandingPage()
    {
        $this->expectException(InvalidLandingPageException::class);
        $this->expectExceptionMessage('Landing page provided is not supported. Please refer to https://developer.paypal.com/docs/api/orders/v2/#definition-order_application_context.');

        $applicationContext = new ApplicationContext();
        $applicationContext->setLandingPage('invalid landing page');
    }

    public function testSetValidLandingPage()
    {
        $applicationContext = new ApplicationContext();
        $this->assertEquals('NO_PREFERENCE', $applicationContext->getLandingPage());
        $applicationContext->setLandingPage('LOGIN');
        $this->assertEquals('LOGIN', $applicationContext->getLandingPage());
        $applicationContext->setLandingPage('BILLING');
        $this->assertEquals('BILLING', $applicationContext->getLandingPage());
        $applicationContext->setLandingPage('NO_PREFERENCE');
        $this->assertEquals('NO_PREFERENCE', $applicationContext->getLandingPage());
    }

    public function testSetsLocale()
    {
        $applicationContext = new ApplicationContext();
        $this->assertEquals('en-US', $applicationContext->getLocale());
        $applicationContext->setLocale('fr');
        $this->assertEquals('fr', $applicationContext->getLocale());
    }

    public function testSetsUrls()
    {
        $applicationContext = new ApplicationContext();
        $this->assertEquals(null, $applicationContext->getReturnUrl());
        $this->assertEquals(null, $applicationContext->getCancelUrl());
        $applicationContext->setReturnUrl('test return url');
        $applicationContext->setCancelUrl('test cancel url');
        $this->assertEquals('test return url', $applicationContext->getReturnUrl());
        $this->assertEquals('test cancel url', $applicationContext->getCancelUrl());
    }

    public function testToArrayWithNoNullValues()
    {
        $applicationContext = new ApplicationContext();
        $expected = [
            'locale' => 'en-US',
            'shipping_preference' => 'NO_SHIPPING',
            'landing_page' => 'NO_PREFERENCE',
            'user_action' => 'CONTINUE',
        ];
        $this->assertEquals($expected, $applicationContext->toArray());
    }

    public function testToArray()
    {
        $applicationContext = new ApplicationContext('Paypal Inc');
        $applicationContext->setCancelUrl('https://site.com/payment/cancel');
        $applicationContext->setReturnUrl('https://site.com/payment/return');
        $applicationContext->setLocale('fr');
        $applicationContext->setShippingPreference('GET_FROM_FILE');
        $applicationContext->setLandingPage('BILLING');
        $applicationContext->setUserAction('PAY_NOW');
        $expected = [
            'brand_name' => 'Paypal Inc',
            'locale' => 'fr',
            'shipping_preference' => 'GET_FROM_FILE',
            'landing_page' => 'BILLING',
            'user_action' => 'PAY_NOW',
            'return_url' => 'https://site.com/payment/return',
            'cancel_url' => 'https://site.com/payment/cancel',
        ];
        $this->assertEquals($expected, $applicationContext->toArray());
    }

    public function testToJson()
    {
        $applicationContext = new ApplicationContext('Paypal Inc');
        $applicationContext->setCancelUrl('https://site.com/payment/cancel');
        $applicationContext->setReturnUrl('https://site.com/payment/return');
        $applicationContext->setLocale('fr');
        $applicationContext->setShippingPreference('GET_FROM_FILE');
        $applicationContext->setLandingPage('BILLING');
        $applicationContext->setUserAction('PAY_NOW');
        $expected = '{
            "brand_name": "Paypal Inc",
            "locale": "fr",
            "shipping_preference": "GET_FROM_FILE",
            "landing_page": "BILLING",
            "user_action": "PAY_NOW",
            "return_url": "https://site.com/payment/return",
            "cancel_url": "https://site.com/payment/cancel"
        }';
        $this->assertJsonStringEqualsJsonString($expected, $applicationContext->toJson());
    }
}
