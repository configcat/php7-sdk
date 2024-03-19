<?php

declare(strict_types=1);

namespace ConfigCat;

/**
 * @internal
 */
final class EvaluationResult
{
    /** @var mixed */
    public $value;

    /** @var string */
    public $variationId;

    /** @var ?mixed[] */
    public $targetingRule;

    /** @var ?mixed[] */
    public $percentageRule;

    /**
     * @param mixed    $value
     * @param ?mixed[] $targetingRule
     * @param ?mixed[] $percentageRule
     */
    public function __construct($value, string $variationId, ?array $targetingRule, ?array $percentageRule)
    {
        $this->value = $value;
        $this->variationId = $variationId;
        $this->targetingRule = $targetingRule;
        $this->percentageRule = $percentageRule;
    }
}
