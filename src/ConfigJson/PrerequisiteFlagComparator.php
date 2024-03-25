<?php

declare(strict_types=1);

namespace ConfigCat\ConfigJson;

/**
 * Prerequisite flag comparison operator used during the evaluation process.
 */
final class PrerequisiteFlagComparator
{
    /** EQUALS - Checks whether the evaluated value of the specified prerequisite flag is equal to the comparison value. */
    public const EQUALS = 0;

    /** NOT EQUALS - Checks whether the evaluated value of the specified prerequisite flag is not equal to the comparison value. */
    public const NOT_EQUALS = 1;

    private function __construct()
    {
    }

    /**
     * @internal
     */
    public static function getValidOrDefault(int $value, ?int $defaultValue = null): ?int
    {
        return self::EQUALS <= $value && $value <= self::NOT_EQUALS ? $value : $defaultValue;
    }
}
