<?php /** @noinspection SpellCheckingInspection */

namespace Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Utils;
use PayPal\Checkout\Environment\ProductionEnvironment;
use PayPal\Checkout\Environment\SandboxEnvironment;
use PayPal\Checkout\Http\AccessTokenRequest;
use PayPal\Checkout\Http\OrderCaptureRequest;
use PayPal\Checkout\Http\PayPalClient;
use PHPUnit\Framework\TestCase;

class PayPalClientTest extends TestCase
{
    /**
     * @var ProductionEnvironment
     */
    protected $environment = null;

    protected $httpClient;

    /**
     * @test
     * @throws GuzzleException
     */
    public function testFetchAccessToken()
    {
        $paypalClient = new PayPalClient($this->environment);
        $paypalClient->setClient($this->httpClient);
        $accessToken = $paypalClient->fetchAccessToken();
        $this->assertEquals('Bearer A21AAFSO5otrlVigoJUQ1p', $accessToken->authorizationString());
    }

    /**
     * @test
     */
    public function testHasAuthorizationHeader()
    {
        $paypalClient = new PayPalClient($this->environment);
        $paypalClient->setClient($this->httpClient);

        $request = new AccessTokenRequest($this->environment);

        $this->assertTrue($paypalClient->hasAuthHeader($request));

        $request = new OrderCaptureRequest('1KC5501443316171H');

        $this->assertFalse($paypalClient->hasAuthHeader($request));
    }

    /**
     * @test
     */
    public function testHasAllSdkHeadersOnProduction()
    {
        $env = new ProductionEnvironment('client_id', 'client_secret');

        $paypalClient = new PayPalClient($env);
        $paypalClient->setClient($this->httpClient);

        $request = new AccessTokenRequest($this->environment);

        $request = $paypalClient->injectSdkHeaders($request);
        $this->assertEquals([
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
        ], $request->getHeaders());
    }

    /**
     * @test
     */
    public function testHasSubSetOfSdkHeadersOnSandbox()
    {
        $env = new SandboxEnvironment('client_id', 'client_secret');

        $paypalClient = new PayPalClient($env);
        $paypalClient->setClient($this->httpClient);

        $request = new AccessTokenRequest($this->environment);

        $request = $paypalClient->injectSdkHeaders($request);
        $this->assertEquals([
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
        ], $request->getHeaders());
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function testHasInvalidToken()
    {
        $paypalClient = new PayPalClient($this->environment);
        $paypalClient->setClient($this->httpClient);

        $this->assertTrue($paypalClient->hasInvalidToken());

        $paypalClient->fetchAccessToken();

        $this->assertFalse($paypalClient->hasInvalidToken());
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function testSendRequest()
    {
        $paypalClient = new PayPalClient($this->environment);
        $paypalClient->setClient($this->httpClient);

        $request = new OrderCaptureRequest('1KC5501443316171H');

        $response = $paypalClient->send($request);

        $result = Utils::jsonDecode((string) $response->getBody());
        $this->assertEquals('1KC5501443316171H', $result->id);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->environment = new ProductionEnvironment('client_id', 'client_secret');

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
        $this->httpClient = new Client(['handler' => $handlerStack]);
    }
}
