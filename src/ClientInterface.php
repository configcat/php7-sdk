<?php

declare(strict_types=1);

namespace ConfigCat;

/**
 * A client for handling configurations provided by ConfigCat.
 */
interface ClientInterface
{
    /**
     * Gets a value of a feature flag or setting identified by the given key.
     *
     * @param string $key          the identifier of the configuration value
     * @param mixed  $defaultValue in case of any failure, this value will be returned
     * @param ?User  $user         the user object to identify the caller
     *
     * @return mixed the configuration value identified by the given key
     */
    public function getValue(string $key, $defaultValue, ?User $user = null);

    /**
     * Gets the value and evaluation details of a feature flag or setting identified by the given key.
     *
     * @param string $key          the identifier of the configuration value
     * @param mixed  $defaultValue in case of any failure, this value will be returned
     * @param ?User  $user         the user object to identify the caller
     *
     * @return EvaluationDetails the configuration value identified by the given key
     */
    public function getValueDetails(string $key, $defaultValue, ?User $user = null): EvaluationDetails;

    /**
     * Gets the key of a setting and its value identified by the given Variation ID (analytics).
     *
     * @param string $variationId the Variation ID
     *
     * @return ?Pair of the key and value of a setting
     */
    public function getKeyAndValue(string $variationId): ?Pair;

    /**
     * Gets a collection of all setting keys.
     *
     * @return string[] of keys
     */
    public function getAllKeys(): array;

    /**
     * Gets the values of all feature flags or settings.
     *
     * @param ?User $user the user object to identify the caller
     *
     * @return mixed[] of values
     */
    public function getAllValues(?User $user = null): array;

    /**
     * Gets the values along with evaluation details of all feature flags and settings.
     *
     * @param ?User $user the user object to identify the caller
     *
     * @return EvaluationDetails[] of evaluation details of all feature flags and settings
     */
    public function getAllValueDetails(?User $user = null): array;

    /**
     * Initiates a force refresh on the cached configuration.
     */
    public function forceRefresh(): RefreshResult;

    /**
     * Sets the default user.
     *
     * @param User $user the default user
     */
    public function setDefaultUser(User $user): void;

    /**
     * Sets the default user to null.
     */
    public function clearDefaultUser(): void;

    /**
     * Gets the Hooks object for subscribing to SDK events.
     *
     * @return Hooks for subscribing to SDK events
     */
    public function hooks(): Hooks;

    /**
     * Configures the SDK to not initiate HTTP requests.
     */
    public function setOffline(): void;

    /**
     * Configures the SDK to allow HTTP requests.
     */
    public function setOnline(): void;

    /**
     * Indicates whether the SDK should be initialized in offline mode or not.
     */
    public function isOffline(): bool;
}
