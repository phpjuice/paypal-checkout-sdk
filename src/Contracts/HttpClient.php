<?php

namespace PayPal\Checkout\Contracts;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

interface HttpClient
{
    /**
     * Send the http request.
     *
     * @param  Request  $request
     * @return Response
     */
    public function send(Request $request): Response;
}
