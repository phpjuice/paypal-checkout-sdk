<?php /** @noinspection SpellCheckingInspection */

namespace Tests\Http;

use PayPal\Checkout\Http\AccessToken;
use PHPUnit\Framework\TestCase;

class AccessTokenTest extends TestCase
{
    /**
     * @test
     */
    public function testCreateAccessToken()
    {
        $accessToken = new AccessToken('A21AAFSO5otrlVigoJUQ1p', 'Bearer', 32400);
        $this->assertEquals('A21AAFSO5otrlVigoJUQ1p', $accessToken->getToken());
        $this->assertEquals('Bearer', $accessToken->getTokenType());
    }

    /**
     * @test
     */
    public function testTokenIsExpired()
    {
        $accessToken = new AccessToken('A21AAFSO5otrlVigoJUQ1p', 'Bearer', 32400);
        $this->assertFalse($accessToken->isExpired());
        $accessToken = new AccessToken('A21AAFSO5otrlVigoJUQ1p', 'Bearer', 0);
        $this->assertTrue($accessToken->isExpired());
    }

    /**
     * @test
     */
    public function testTokenAuthorizationString()
    {
        $accessToken = new AccessToken('Token', 'Bearer', 32400);
        $this->assertEquals('Bearer Token', $accessToken->authorizationString());
    }
}
