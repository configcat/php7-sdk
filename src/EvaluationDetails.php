<?php

declare(strict_types=1);

namespace ConfigCat;

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
    private $error;

    /** @var float */
    private $fetchTimeUnixMilliseconds;

    /** @var ?mixed[] */
    private $matchedEvaluationRule;

    /** @var ?mixed[] */
    private $matchedEvaluationPercentageRule;

    /**
     * @param mixed    $value
     * @param ?mixed[] $matchedEvaluationRule
     * @param ?mixed[] $matchedEvaluationPercentageRule
     *
     * @internal
     */
    public function __construct(
        string $key,
        ?string $variationId,
        $value,
        ?User $user,
        bool $isDefaultValue,
        ?string $error,
        float $fetchTimeUnixMilliseconds,
        ?array $matchedEvaluationRule,
        ?array $matchedEvaluationPercentageRule
    ) {
        $this->key = $key;
        $this->variationId = $variationId;
        $this->value = $value;
        $this->user = $user;
        $this->isDefaultValue = $isDefaultValue;
        $this->error = $error;
        $this->fetchTimeUnixMilliseconds = $fetchTimeUnixMilliseconds;
        $this->matchedEvaluationRule = $matchedEvaluationRule;
        $this->matchedEvaluationPercentageRule = $matchedEvaluationPercentageRule;
    }

    /**
     * @param mixed $value
     *
     * @internal
     */
    public static function fromError(string $key, $value, ?User $user, ?string $error): EvaluationDetails
    {
        return new EvaluationDetails(
            $key,
            null,
            $value,
            $user,
            true,
            $error,
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
     * @return ?string in case of an error, the error message
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @return float the last download time of the current config in unix milliseconds format
     */
    public function getFetchTimeUnixMilliseconds(): float
    {
        return $this->fetchTimeUnixMilliseconds;
    }

    /**
     * @return ?mixed[] the targeting rule the evaluation was based on
     */
    public function getMatchedEvaluationRule(): ?array
    {
        return $this->matchedEvaluationRule;
    }

    /**
     * @return ?mixed[] the percentage rule the evaluation was based on
     */
    public function getMatchedEvaluationPercentageRule(): ?array
    {
        return $this->matchedEvaluationPercentageRule;
    }
}
