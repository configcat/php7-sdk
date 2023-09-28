<?php

namespace ConfigCat\Http;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Bridge class for PHP 7.1
 */
class GuzzleClient extends Client implements ClientInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return self::send($request);
    }
}
