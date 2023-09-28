<?php

declare(strict_types=1);

namespace ConfigCat;

use ConfigCat\Attributes\Config;
use ConfigCat\Attributes\Preferences;
use ConfigCat\Cache\ConfigEntry;
use ConfigCat\Http\FetchClientInterface;
use ConfigCat\Http\GuzzleFetchClient;
use ConfigCat\Log\InternalLogger;
use InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * Class ConfigFetcher This class is used to fetch the latest configuration.
 * @package ConfigCat
 * @internal
 */
final class ConfigFetcher
{
    const ETAG_HEADER = "ETag";
    const CONFIG_JSON_NAME = "config_v5.json";

    const GLOBAL_URL = "https://cdn-global.configcat.com";
    const EU_ONLY_URL = "https://cdn-eu.configcat.com";

    const NO_REDIRECT = 0;
    const SHOULD_REDIRECT = 1;
    const FORCE_REDIRECT = 2;

    /** @var InternalLogger */
    private $logger;
    /** @var string */
    private $urlPath;
    /** @var string */
    private $baseUrl;
    /** @var bool */
    private $urlIsCustom = false;
    /** @var string */
    private $userAgentHeader;
    /** @var FetchClientInterface */
    private $client;

    /**
     * ConfigFetcher constructor.
     *
     * @param string         $sdkKey  the SDK Key used to communicate with the ConfigCat services
     * @param InternalLogger $logger  the logger instance
     * @param mixed[]        $options additional configuration options
     *
     * @throws InvalidArgumentException if the $sdkKey, the $logger or the $cache is not legal
     */
    public function __construct(string $sdkKey, InternalLogger $logger, array $options = [])
    {
        if (empty($sdkKey)) {
            throw new InvalidArgumentException("sdkKey cannot be empty.");
        }

        $this->urlPath = sprintf("configuration-files/%s/" . self::CONFIG_JSON_NAME, $sdkKey);

        if (isset($options[ClientOptions::BASE_URL]) && !empty($options[ClientOptions::BASE_URL])) {
            $this->baseUrl = $options[ClientOptions::BASE_URL];
            $this->urlIsCustom = true;
        } elseif (isset($options[ClientOptions::DATA_GOVERNANCE]) &&
            DataGovernance::isValid($options[ClientOptions::DATA_GOVERNANCE])) {
            $this->baseUrl = DataGovernance::isEuOnly($options[ClientOptions::DATA_GOVERNANCE])
                ? self::EU_ONLY_URL
                : self::GLOBAL_URL;
        } else {
            $this->baseUrl = self::GLOBAL_URL;
        }

        $this->userAgentHeader = 'ConfigCat-PHP/'.ConfigCatClient::SDK_VERSION;

        $this->logger = $logger;

        $this->client = (isset($options[ClientOptions::FETCH_CLIENT])
            && $options[ClientOptions::FETCH_CLIENT] instanceof FetchClientInterface)
            ? $options[ClientOptions::FETCH_CLIENT]
            : GuzzleFetchClient::create($options);
    }

    /**
     * Gets the latest configuration from the network.
     *
     * @param string|null $etag The ETag.
     *
     * @return FetchResponse An object describing the result of the fetch.
     */
    public function fetch(?string $etag): FetchResponse
    {
        return $this->executeFetch($etag, $this->baseUrl, 2);
    }

    private function executeFetch(?string $etag, string $url, int $executionCount): FetchResponse
    {
        $response = $this->sendConfigFetchRequest($etag, $url);

        if (!$response->isFetched() || !isset($response->getConfigEntry()->getConfig()[Config::PREFERENCES])) {
            return $response;
        }

        $newUrl = "";
        if (isset($response->getConfigEntry()->getConfig()[Config::PREFERENCES][Preferences::BASE_URL])) {
            $newUrl = $response->getConfigEntry()->getConfig()[Config::PREFERENCES][Preferences::BASE_URL];
        }
        if (empty($newUrl) || $newUrl == $url) {
            return $response;
        }

        $preferences = $response->getConfigEntry()->getConfig()[Config::PREFERENCES];
        $redirect = $preferences[Preferences::REDIRECT];
        if ($this->urlIsCustom && $redirect != self::FORCE_REDIRECT) {
            return $response;
        }

        if ($redirect == self::NO_REDIRECT) {
            return $response;
        }

        if ($redirect == self::SHOULD_REDIRECT) {
            $this->logger->warning(
                "The `dataGovernance` parameter specified at the client initialization is ".
                "not in sync with the preferences on the ConfigCat Dashboard. " .
                "Read more: https://configcat.com/docs/advanced/data-governance/",
                [
                    'event_id' => 3002
                ]
            );
        }

        if ($executionCount > 0) {
            return $this->executeFetch($etag, $newUrl, $executionCount - 1);
        }

        $this->logger->error("Redirection loop encountered while trying to fetch config JSON. Please contact us at https://configcat.com/support/", [
            'event_id' => 1104
        ]);
        return $response;
    }

    private function sendConfigFetchRequest(?string $etag, string $url): FetchResponse
    {
        $configJsonUrl = sprintf('%s/%s', $url, $this->urlPath);
        $request = $this->client->createRequest('GET', $configJsonUrl)
            ->withHeader('X-ConfigCat-UserAgent', $this->userAgentHeader)
        ;

        if (!empty($etag)) {
            $request = $request->withHeader('If-None-Match', $etag);
        }

        try {
            $fetchClient = $this->client->getClient();
            $response = $fetchClient->sendRequest($request);
            $statusCode = $response->getStatusCode();

            if ($response->hasHeader(self::ETAG_HEADER)) {
                $etag = $response->getHeader(self::ETAG_HEADER)[0];
            }

            if ($statusCode >= 200 && $statusCode < 300) {
                $this->logger->debug("Fetch was successful: new config fetched.");
                $entry = ConfigEntry::fromConfigJson($response->getBody()->getContents(), $etag, Utils::getUnixMilliseconds());
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $message = "Fetching config JSON was successful but the HTTP response content was invalid. JSON error: " . json_last_error_msg();
                    $messageCtx = [
                        'event_id' => 1105
                    ];
                    $this->logger->error($message, $messageCtx);
                    return FetchResponse::failure(InternalLogger::format($message, $messageCtx));
                }
                return FetchResponse::success($entry);
            } elseif ($statusCode === 304) {
                $this->logger->debug("Fetch was successful: config not modified.");
                return FetchResponse::notModified();
            }

            $message = "Your SDK Key seems to be wrong. You can find the valid SDK Key at https://app.configcat.com/sdkkey. " .
                "Received unexpected response: " . $statusCode;
            $messageCtx = [
                'event_id' => 1100
            ];
            $this->logger->error($message, $messageCtx);
            return FetchResponse::failure(InternalLogger::format($message, $messageCtx));
        } catch (ClientExceptionInterface|\Exception $exception) {
            $message = 'Unexpected error occurred while trying to fetch config JSON.';
            $messageCtx = ['event_id' => 1103, 'exception' => $exception];
            $this->logger->error($message, $messageCtx);

            return FetchResponse::failure($message);
        }
    }
}
