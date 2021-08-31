<?php /** @noinspection SpellCheckingInspection */

namespace Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Utils;
use PayPal\Checkout\Environment\ProductionEnvironment;
use PayPal\Checkout\Http\AccessTokenRequest;

beforeEach(function () {
    $this->environment = new ProductionEnvironment('client_id', 'client_secret');
});

it('has correct request uri', function () {
    $request = new AccessTokenRequest($this->environment);
    expect((string) $request->getUri())->toBe('/v1/oauth2/token');
});

it('has correct rquest method', function () {
    $request = new AccessTokenRequest($this->environment);
    expect($request->getMethod())->toBe('POST');
});

it('has correct request headers', function () {
    $request = new AccessTokenRequest($this->environment);
    expect($request->getHeaderLine('Content-Type'))->toBe('application/x-www-form-urlencoded');
});

it('has basic auth request headers', function () {
    $request = new AccessTokenRequest($this->environment);
    $expected = 'Basic '.$this->environment->basicAuthorizationString();
    expect($request->getHeaderLine('Authorization'))->toBe($expected);
});

it('has correct data with body', function () {
    $request = new AccessTokenRequest($this->environment);
    $expected = http_build_query(['grant_type' => 'client_credentials']);
    expect((string) $request->getBody())->toBe($expected);
});

it('can excute a request', function () {
    $mockResponse = json_encode([
        'access_token' => 'A21AAFSO5otrlVigoJUQ1p',
        'token_type' => 'Bearer',
        'expires_in' => 32400,
    ]);
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], $mockResponse),
    ]);
    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $response = $client->send(new AccessTokenRequest($this->environment));

    expect($response->getStatusCode())->toBe(200);

    $body = Utils::jsonDecode((string) $response->getBody());
    expect($body->access_token)->toBe('A21AAFSO5otrlVigoJUQ1p');
    expect($body->token_type)->toBe('Bearer');
    expect($body->expires_in)->toBe(32400);
});
