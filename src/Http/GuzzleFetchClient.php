<?php

declare(strict_types=1);

namespace ConfigCat\Http;

use ConfigCat\ClientOptions;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

class GuzzleFetchClient implements FetchClientInterface
{
    /** @var GuzzleClient */
    private $client;

    /**
     * @param array<string, mixed> $options
     */
    private function __construct(array $options = [])
    {
        if (!isset($options[RequestOptions::CONNECT_TIMEOUT])) {
            $options[RequestOptions::CONNECT_TIMEOUT] = 10;
        }

        if (!isset($options[RequestOptions::TIMEOUT])) {
            $options[RequestOptions::TIMEOUT] = 30;
        }

        if (isset($options[ClientOptions::CUSTOM_HANDLER])) {
            $options['handler'] = $options[ClientOptions::CUSTOM_HANDLER];
        }

        $requestOptions = isset($options[ClientOptions::REQUEST_OPTIONS])
        && is_array($options[ClientOptions::REQUEST_OPTIONS])
        && !empty($options[ClientOptions::REQUEST_OPTIONS])
            ? $options[ClientOptions::REQUEST_OPTIONS]
            : [];

        $this->client = new GuzzleClient(array_merge($options, $requestOptions));
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    public function createRequest(string $method, string $uri): RequestInterface
    {
        return new Request($method, $uri);
    }

    /**
     * Constructs a \GuzzleHttp\Client to handle the HTTP requests initiated by the SDK.
     *
     * @param array<string, mixed> $options options for the underlying \GuzzleHttp\Client
     *
     * @return FetchClientInterface the constructed fetch client that works with \GuzzleHttp\Client
     */
    public static function create(array $options = []): FetchClientInterface
    {
        return new GuzzleFetchClient($options);
    }
}
