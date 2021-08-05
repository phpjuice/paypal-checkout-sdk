<?php

namespace Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Utils;
use PayPal\Checkout\Http\OrderCreateRequest;
use PayPal\Checkout\Orders\ApplicationContext;
use PayPal\Checkout\Orders\Item;
use PayPal\Checkout\Orders\Order;
use PayPal\Checkout\Orders\PurchaseUnit;
use PHPUnit\Framework\TestCase;

class OrderCreateRequestTest extends TestCase
{
    /**
     * @test
     */
    public function testHasCorrectUri()
    {
        $request = new OrderCreateRequest();
        $this->assertEquals('/v2/checkout/orders', $request->getUri());
    }

    /**
     * @test
     */
    public function testHasCorrectMethod()
    {
        $request = new OrderCreateRequest();
        $this->assertEquals('POST', $request->getMethod());
    }

    /**
     * @test
     */
    public function testHasCorrectHeaders()
    {
        $request = new OrderCreateRequest();
        $this->assertEquals('application/json', $request->getHeaderLine('Content-Type'));
        $this->assertEquals('return=representation', $request->getHeaderLine('Prefer'));
    }

    /**
     * @test
     */
    public function testHasCorrectDataWithGetBody()
    {
        $purchase_unit = new PurchaseUnit('USD', 100.00);
        $purchase_unit->addItem(new Item('Item 1', 'USD', 100.00, 1));
        $order = new Order('CAPTURE');
        $order->addPurchaseUnit($purchase_unit);
        $application_context = new ApplicationContext('Paypal Inc', 'en');
        $application_context->setUserAction('PAY_NOW');
        $application_context->setReturnUrl('https://site.com/payment/return');
        $application_context->setCancelUrl('https://site.com/payment/cancel');
        $order->setApplicationContext($application_context);

        $request = new OrderCreateRequest($order);
        $this->assertEquals((string) $order, (string) $request->getBody());
        $this->assertEquals($order->toArray(), Utils::jsonDecode($request->getBody(), true));
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function testExecuteRequest()
    {
        $mockResponse = Utils::jsonEncode([
            'id' => '1KC5501443316171H',
            'intent' => 'CAPTURE',
            'status' => 'CREATED',
        ]);
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], $mockResponse),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $purchase_unit = new PurchaseUnit('USD', 100.00);
        $purchase_unit->addItem(new Item('Item 1', 'USD', 100.00, 1));
        $order = new Order('CAPTURE');
        $order->addPurchaseUnit($purchase_unit);
        $response = $client->send(new OrderCreateRequest($order));

        $this->assertEquals(200, $response->getStatusCode());

        $result = Utils::jsonDecode((string) $response->getBody());
        $this->assertEquals('1KC5501443316171H', $result->id);
        $this->assertEquals('CAPTURE', $result->intent);
        $this->assertEquals('CREATED', $result->status);
    }
}
