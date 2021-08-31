<?php

namespace Tests\Environment;

use PayPal\Checkout\Environment\ProductionEnvironment;
use PayPal\Checkout\Environment\SandboxEnvironment;

it('creates a sandbox environment', function () {
    $env = new SandboxEnvironment('client_id', 'client_secret');
    expect($env->baseUrl())->toBe('https://api.sandbox.paypal.com');
});

it('creates production environment', function () {
    $env = new ProductionEnvironment('client_id', 'client_secret');
    expect($env->baseUrl())->toBe('https://api.paypal.com');
});

it('has basic authorization string', function () {
    $env1 = new SandboxEnvironment('client_id', 'client_secret');
    $env2 = new ProductionEnvironment('client_id', 'client_secret');
    $expected = base64_encode('client_id:client_secret');
    expect($env1->basicAuthorizationString())->toBe($expected);
    expect($env2->basicAuthorizationString())->toBe($expected);
});
