<?php

namespace PayPal\Checkout\Http;

use PayPal\Checkout\Contracts\Environment;

class AccessTokenRequest extends PaypalRequest
{
    public function __construct(Environment $environment, $refresh_token = null)
    {
        $headers = [
            'Authorization' => 'Basic '.$environment->basicAuthorizationString(),
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $body = http_build_query(['grant_type' => 'client_credentials']);

        // if refresh token is provided we use it to get an access token
        if (!is_null($refresh_token)) {
            $body = http_build_query([
                'grant_type' => 'refresh_token',
                'refresh_token' => $refresh_token,
            ]);
        }
        parent::__construct('POST', '/v1/oauth2/token', $headers, $body);
    }
}
