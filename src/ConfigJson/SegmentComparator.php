<?php

declare(strict_types=1);

namespace ConfigCat\ConfigJson;

/**
 * Segment comparison operator used during the evaluation process.
 */
abstract class SegmentComparator
{
    /** IS IN SEGMENT - Checks whether the conditions of the specified segment are evaluated to true. */
    public const IS_IN = 0;

    /** IS NOT IN SEGMENT - Checks whether the conditions of the specified segment are evaluated to false. */
    public const IS_NOT_IN = 1;

    /**
     * @internal
     */
    public static function getValidOrDefault(int $value, ?int $defaultValue = null): ?int {
        return self::IS_IN <= $value && $value <= self::IS_NOT_IN ? $value : $defaultValue;
    }
}
