<?php

namespace PayPal\Checkout\Contracts;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

interface HttpClient
{
    /**
     * Send the http request.
     *
     * @param  Request  $request
     * @return Response
     * @throws GuzzleException|RequestException
     */
    public function send(Request $request): Response;
}
