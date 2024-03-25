<?php

declare(strict_types=1);

namespace ConfigCat\ConfigJson;

/**
 * Setting type.
 */
final class SettingType
{
    /** On/off type (feature flag). */
    public const BOOLEAN = 0;

    /** Text type. */
    public const STRING = 1;

    /** Whole number type. */
    public const INT = 2;

    /** Decimal number type. */
    public const DOUBLE = 3;

    private function __construct()
    {
    }

    /**
     * @internal
     */
    public static function getValidOrDefault(int $value, ?int $defaultValue = null): ?int
    {
        return self::BOOLEAN <= $value && $value <= self::DOUBLE ? $value : $defaultValue;
    }

    /**
     * @internal
     */
    public static function getName(int $value): string
    {
        switch ($value) {
            case self::BOOLEAN: return 'BOOLEAN';

            case self::STRING: return 'STRING';

            case self::INT: return 'INT';

            case self::DOUBLE: return 'DOUBLE';

            default: return '<invalid type>';
        }
    }
}
