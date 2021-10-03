<?php /** @noinspection SpellCheckingInspection */

namespace Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Utils;
use PayPal\Checkout\Environment\ProductionEnvironment;
use PayPal\Checkout\Environment\SandboxEnvironment;
use PayPal\Checkout\Http\AccessTokenRequest;
use PayPal\Checkout\Http\OrderCaptureRequest;
use PayPal\Checkout\Http\PayPalClient;

it("fetches access token", function () {
    $env = new ProductionEnvironment('client_id', 'client_secret');
    $paypalClient = new PayPalClient($env);
    $paypalClient->setClient($this->client);
    /** @noinspection PhpUnhandledExceptionInspection */
    $accessToken = $paypalClient->fetchAccessToken();
    expect($accessToken->authorizationString())->toBe('Bearer A21AAFSO5otrlVigoJUQ1p');
});

it("has authorization header", function () {
    $env = new ProductionEnvironment('client_id', 'client_secret');
    $paypalClient = new PayPalClient($env);
    $paypalClient->setClient($this->client);

    $request = new AccessTokenRequest($env);
    expect($paypalClient->hasAuthHeader($request))->toBeTrue();

    $request = new OrderCaptureRequest('1KC5501443316171H');

    expect($paypalClient->hasAuthHeader($request))->toBeFalse();
});


it("has all sdk headers on production", function () {
    $env = new ProductionEnvironment('client_id', 'client_secret');
    $paypalClient = new PayPalClient($env);
    $paypalClient->setClient($this->client);

    $request = new AccessTokenRequest($env);
    $request = $paypalClient->injectSdkHeaders($request);
    expect($request->getHeaders())->toBe([
        'Authorization' => [
            'Basic Y2xpZW50X2lkOmNsaWVudF9zZWNyZXQ=',
        ],
        'Accept' => [
            'application/json',
        ],
        'Content-Type' => [
            'application/x-www-form-urlencoded',
        ],
        'sdk_name' => [
            'Checkout SDK',
        ],
        'sdk_version' => [
            '1.0.0',
        ],
        'sdk_tech_stack' => [
            'PHP '.PHP_VERSION,
        ],
    ]);
});


it("has only a subset of sdk headers on sandbox", function () {
    $env = new SandboxEnvironment('client_id', 'client_secret');
    $paypalClient = new PayPalClient($env);
    $paypalClient->setClient($this->client);

    $request = new AccessTokenRequest($env);
    $request = $paypalClient->injectSdkHeaders($request);
    expect($request->getHeaders())->toBe([
        'Authorization' => [
            'Basic Y2xpZW50X2lkOmNsaWVudF9zZWNyZXQ=',
        ],
        'Accept' => [
            'application/json',
        ],
        'Content-Type' => [
            'application/x-www-form-urlencoded',
        ],
        'sdk_name' => [
            'Checkout SDK',
        ],
        'sdk_version' => [
            '1.0.0',
        ],
    ]);
});


it("tests has invalid token method", function () {
    $env = new ProductionEnvironment('client_id', 'client_secret');
    $paypalClient = new PayPalClient($env);
    $paypalClient->setClient($this->client);

    expect($paypalClient->hasInvalidToken())->toBeTrue();

    /** @noinspection PhpUnhandledExceptionInspection */
    $paypalClient->fetchAccessToken();

    expect($paypalClient->hasInvalidToken())->toBeFalse();
});


it("can execute request", function () {
    $env = new ProductionEnvironment('client_id', 'client_secret');
    $paypalClient = new PayPalClient($env);
    $paypalClient->setClient($this->client);

    $request = new OrderCaptureRequest('1KC5501443316171H');

    /** @noinspection PhpUnhandledExceptionInspection */
    $response = $paypalClient->send($request);

    $result = Utils::jsonDecode((string) $response->getBody(), true);
    expect($result['id'])->toBe('1KC5501443316171H');
});


beforeEach(function () {
    $response1 = json_encode([
        'access_token' => 'A21AAFSO5otrlVigoJUQ1p',
        'token_type' => 'Bearer',
        'expires_in' => 32400,
    ]);

    $response2 = json_encode([
        'id' => '1KC5501443316171H',
        'intent' => 'CAPTURE',
        'status' => 'CREATED',
    ]);

    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], $response1),
        new Response(200, ['Content-Type' => 'application/json'], $response2),
    ]);

    $handlerStack = HandlerStack::create($mock);

    $this->client = new Client(['handler' => $handlerStack]);
});
