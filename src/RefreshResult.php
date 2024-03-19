<?php

declare(strict_types=1);

namespace ConfigCat;

/**
 * Represents the result of forceRefresh().
 */
class RefreshResult
{
    /** @var bool */
    private $isSuccess;

    /** @var ?string */
    private $error;

    /**
     * @internal
     */
    public function __construct(bool $isSuccess, ?string $error)
    {
        $this->isSuccess = $isSuccess;
        $this->error = $error;
    }

    /**
     * Returns true when the refresh was successful.
     *
     * @return bool true when the refresh was successful
     */
    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    /**
     * Returns the reason if the refresh fails.
     *
     * @return ?string the reason of the failure
     */
    public function getError(): ?string
    {
        return $this->error;
    }
}
