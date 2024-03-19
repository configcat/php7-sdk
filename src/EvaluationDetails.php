<?php

declare(strict_types=1);

namespace ConfigCat;

use Throwable;

class EvaluationDetails
{
    /** @var string */
    private $key;

    /** @var ?string */
    private $variationId;

    /** @var mixed */
    private $value;

    /** @var ?User */
    private $user;

    /** @var bool */
    private $isDefaultValue;

    /** @var ?string */
    private $errorMessage;

    /** @var ?Throwable */
    private $errorException;

    /** @var float */
    private $fetchTimeUnixMilliseconds;

    /** @var ?array<string, mixed> */
    private $matchedTargetingRule;

    /** @var ?array<string, mixed> */
    private $matchedPercentageOption;

    /**
     * @param mixed    $value
     * @param ?array<string, mixed> $matchedTargetingRule
     * @param ?array<string, mixed> $matchedPercentageOption
     *
     * @internal
     */
    public function __construct(
        string $key,
        ?string $variationId,
        $value,
        ?User $user,
        bool $isDefaultValue,
        ?string $errorMessage,
        ?Throwable $errorException,
        float $fetchTimeUnixMilliseconds,
        ?array $matchedTargetingRule,
        ?array $matchedPercentageOption
    ) {
        $this->key = $key;
        $this->variationId = $variationId;
        $this->value = $value;
        $this->user = $user;
        $this->isDefaultValue = $isDefaultValue;
        $this->errorMessage = $errorMessage;
        $this->errorException = $errorException;
        $this->fetchTimeUnixMilliseconds = $fetchTimeUnixMilliseconds;
        $this->matchedTargetingRule = $matchedTargetingRule;
        $this->matchedPercentageOption = $matchedPercentageOption;
    }

    /**
     * @param mixed $value
     *
     * @internal
     */
    public static function fromError(string $key, $value, ?User $user, string $errorMessage, ?Throwable $errorException = null): EvaluationDetails
    {
        return new EvaluationDetails(
            $key,
            null,
            $value,
            $user,
            true,
            $errorMessage,
            $errorException,
            0,
            null,
            null
        );
    }

    /**
     * @return string the key of the evaluated feature flag or setting
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return ?string the variation ID (analytics)
     */
    public function getVariationId(): ?string
    {
        return $this->variationId;
    }

    /**
     * @return mixed the evaluated value of the feature flag or setting
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return ?User the user object that was used for evaluation
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return bool true when the default value passed to getValueDetails() is returned due to an error
     */
    public function isDefaultValue(): bool
    {
        return $this->isDefaultValue;
    }

    /**
     * @return ?string error message in case evaluation failed
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @return ?Throwable the `Throwable` object related to the error in case evaluation failed (if any)
     */
    public function getErrorException(): ?Throwable
    {
        return $this->errorException;
    }

    /**
     * @return float the last download time of the current config in unix milliseconds format
     */
    public function getFetchTimeUnixMilliseconds(): float
    {
        return $this->fetchTimeUnixMilliseconds;
    }

    /**
     * @return ?array<string, mixed> the targeting rule (if any) that matched during the evaluation and was used to return the evaluated value
     */
    public function getMatchedTargetingRule(): ?array
    {
        return $this->matchedTargetingRule;
    }

    /**
     * @return ?array<string, mixed> the percentage option (if any) that was used to select the evaluated value
     */
    public function getMatchedPercentageOption(): ?array
    {
        return $this->matchedPercentageOption;
    }
}
