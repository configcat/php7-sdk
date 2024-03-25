<?php

declare(strict_types=1);

namespace ConfigCat\ConfigJson;

use UnexpectedValueException;

/**
 * Redirect mode.
 */
final class RedirectMode
{
    public const NO = 0;
    public const SHOULD = 1;
    public const FORCE = 2;

    private function __construct()
    {
    }

    /**
     * @internal
     */
    public static function getValidOrDefault(int $value, ?int $defaultValue = null): ?int
    {
        return self::NO <= $value && $value <= self::FORCE ? $value : $defaultValue;
    }

    /**
     * @internal
     */
    public static function ensureValid(int $value): int
    {
        if (is_null(self::getValidOrDefault($value))) {
            $className = self::class;

            throw new UnexpectedValueException("{$value} is not a valid value for {$className}");
        }

        return $value;
    }
}
