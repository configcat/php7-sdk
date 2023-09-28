<?php

namespace ConfigCat\Http;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GuzzleClient extends Client implements ClientInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return self::send($request);
    }
}
