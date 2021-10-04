<?php /** @noinspection SpellCheckingInspection */

namespace Tests\Http;

use PayPal\Checkout\Http\AccessToken;

it('testCreateAccessToken', function () {
    $accessToken = new AccessToken('A21AAFSO5otrlVigoJUQ1p', 'Bearer', 32400);
    expect($accessToken->getToken())->toBe('A21AAFSO5otrlVigoJUQ1p');
    expect($accessToken->getTokenType())->toBe('Bearer');
});

it('testTokenIsExpired', function () {
    $accessToken = new AccessToken('A21AAFSO5otrlVigoJUQ1p', 'Bearer', 32400);
    expect($accessToken->isExpired())->toBeFalse();
    $accessToken = new AccessToken('A21AAFSO5otrlVigoJUQ1p', 'Bearer', 0);
    expect($accessToken->isExpired())->toBeTrue();
});

it('testTokenAuthorizationString', function () {
    $accessToken = new AccessToken('Token', 'Bearer', 32400);
    expect($accessToken->authorizationString())->toEqual('Bearer Token');
});
