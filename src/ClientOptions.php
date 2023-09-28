<?php

declare(strict_types=1);

namespace ConfigCat;

/**
 * Contains the configuration options of the \ConfigCat\ConfigCatClient.
 */
final class ClientOptions
{
    /**
     * The base ConfigCat CDN url.
     */
    const BASE_URL = "base-url";

    /**
     * A \Psr\Log\LoggerInterface implementation used for logging.
     */
    const LOGGER = "logger";

    /**
     * Default: Warning. Sets the internal log level.
     */
    const LOG_LEVEL = "log-level";

    /**
     * A \ConfigCat\ConfigCache implementation used for caching.
     */
    const CACHE = "cache";

    /**
     * Array of exception classes that should be ignored from logs.
     */
    const EXCEPTIONS_TO_IGNORE = "exceptions-to-ignore";

    /**
     * Sets how frequent the cached configuration should be refreshed in seconds.
     */
    const CACHE_REFRESH_INTERVAL = "cache-refresh-interval";

    /**
     * Additional options for Guzzle http requests.
     * https://docs.guzzlephp.org/en/stable/request-options.html
     *
     * @deprecated use \ConfigCat\ClientOptions::FETCH_CLIENT option instead with \ConfigCat\Http\GuzzleFetchClient::create()
     */
    const REQUEST_OPTIONS = "request-options";

    /**
     * A custom callable Guzzle http handler.
     *
     * @deprecated use \ConfigCat\ClientOptions::FETCH_CLIENT option instead with \ConfigCat\Http\GuzzleFetchClient::create()
     */
    const CUSTOM_HANDLER = "custom-handler";

    /**
     * A \ConfigCat\Http\FetchClientInterface implementation that wraps an actual HTTP client used
     * to make HTTP requests towards ConfigCat.
     * When it's not set, \ConfigCat\Http\GuzzleFetchClient is used by default.
     */
    public const FETCH_CLIENT = 'fetch-client';

    /**
     * Default: Global. Set this parameter to be in sync with the Data Governance
     * preference on the Dashboard: https://app.configcat.com/organization/data-governance
     * (Only Organization Admins can access)
     */
    const DATA_GOVERNANCE = "data-governance";

    /**
     * A \ConfigCat\Override\OverrideDataSource implementation used to override
     * feature flags & settings.
     */
    const FLAG_OVERRIDES = "flag-overrides";

    /**
     * A \ConfigCat\User as default user.
     */
    const DEFAULT_USER = "default-user";

    /**
     * Default: false. Indicates whether the SDK should be initialized in offline mode or not.
     */
    const OFFLINE = "offline";
}
