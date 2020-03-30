<?php

namespace Tests\Environment;

use PayPal\Checkout\Environment\ProductionEnvironment;
use PayPal\Checkout\Environment\SandboxEnvironment;
use PHPUnit\Framework\TestCase;

class PayPalEnvironmentTest extends TestCase
{
    public function testCreateSandboxEnvironment()
    {
        $env = new SandboxEnvironment('client_id', 'client_secret');
        $this->assertEquals('https://api.sandbox.paypal.com', $env->baseUrl());
    }

    public function testCreateProductionEnvironment()
    {
        $env = new ProductionEnvironment('client_id', 'client_secret');
        $this->assertEquals('https://api.paypal.com', $env->baseUrl());
    }

    public function testBasicAuthorizationString()
    {
        $env1 = new SandboxEnvironment('client_id', 'client_secret');
        $env2 = new ProductionEnvironment('client_id', 'client_secret');
        $expected = base64_encode('client_id:client_secret');
        $this->assertEquals($expected, $env1->basicAuthorizationString());
        $this->assertEquals($expected, $env2->basicAuthorizationString());
    }
}
