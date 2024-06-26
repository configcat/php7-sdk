<?php

declare(strict_types=1);

namespace ConfigCat\ConfigJson;

use ConfigCat\Utils;
use UnexpectedValueException;

/**
 * Represents the JSON keys of a targeting rule.
 */
final class TargetingRule
{
    public const CONDITIONS = 'c';
    public const SIMPLE_VALUE = 's';
    public const PERCENTAGE_OPTIONS = 'p';

    private function __construct()
    {
    }

    /**
     * @param mixed $targetingRules
     *
     * @throws UnexpectedValueException
     *
     * @return list<array<string, mixed>>
     *
     * @internal
     */
    public static function ensureList($targetingRules): array
    {
        if (!is_array($targetingRules) || !Utils::array_is_list($targetingRules)) {
            throw new UnexpectedValueException('Targeting rule list is invalid.');
        }

        return $targetingRules;
    }

    /**
     * @param mixed $targetingRule
     *
     * @throws UnexpectedValueException
     *
     * @return array<string, mixed>
     *
     * @internal
     */
    public static function ensure($targetingRule): array
    {
        if (!is_array($targetingRule)) {
            throw new UnexpectedValueException('Targeting rule is missing or invalid.');
        }

        return $targetingRule;
    }

    /**
     * @param array<string, mixed> $targetingRule
     *
     * @throws UnexpectedValueException
     *
     * @internal
     */
    public static function hasPercentageOptions(array $targetingRule, bool $throwIfInvalid = true): ?bool
    {
        $simpleValue = $targetingRule[self::SIMPLE_VALUE] ?? null;
        $percentageOptions = $targetingRule[self::PERCENTAGE_OPTIONS] ?? null;

        if (isset($simpleValue)) {
            if (!isset($percentageOptions) && is_array($simpleValue)) {
                return false;
            }
        } elseif (is_array($percentageOptions) && Utils::array_is_list($percentageOptions) && count($percentageOptions) > 0) {
            return true;
        }

        if ($throwIfInvalid) {
            throw new UnexpectedValueException('Targeting rule THEN part is missing or invalid.');
        }

        return null;
    }
}
