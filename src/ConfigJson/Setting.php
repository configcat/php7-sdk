<?php

declare(strict_types=1);

namespace ConfigCat\ConfigJson;

use stdClass;
use UnexpectedValueException;

/**
 * Represents the JSON keys of a setting.
 */
final class Setting extends SettingValueContainer
{
    public const TYPE = 't';
    public const PERCENTAGE_OPTIONS_ATTRIBUTE = 'a';
    public const TARGETING_RULES = 'r';
    public const PERCENTAGE_OPTIONS = 'p';

    /**
     * @internal
     */
    public const CONFIG_JSON_SALT = '__configJsonSalt';

    /**
     * @internal
     */
    public const CONFIG_SEGMENTS = '__configSegments';

    protected function __construct()
    {
    }

    /**
     * @param mixed $settings
     *
     * @throws UnexpectedValueException
     *
     * @return array<string, mixed>
     *
     * @internal
     */
    public static function ensureMap($settings): array
    {
        if (!is_array($settings)) {
            throw new UnexpectedValueException('Setting map is invalid.');
        }

        return $settings;
    }

    /**
     * @param mixed $setting
     *
     * @throws UnexpectedValueException
     *
     * @return array<string, mixed>
     *
     * @internal
     */
    public static function ensure($setting): array
    {
        if (!is_array($setting)) {
            throw new UnexpectedValueException('Setting is missing or invalid.');
        }

        return $setting;
    }

    /**
     * @param array<string, mixed> $setting
     *
     * @return null|int|stdClass
     *
     * @internal
     */
    public static function getType(array $setting, bool $throwIfInvalid = true)
    {
        $settingType = $setting[self::TYPE] ?? null;
        if ($settingType === self::unsupportedTypeToken()) {
            return $settingType;
        }

        $settingType = SettingType::getValidOrDefault($settingType);
        if (isset($settingType)) {
            return $settingType;
        }

        if ($throwIfInvalid) {
            throw new UnexpectedValueException('Setting type is missing or invalid.');
        }

        return null;
    }

    /**
     * @param mixed $value
     *
     * @return array<string, mixed>
     *
     * @internal
     */
    public static function fromValue($value): array
    {
        if (is_bool($value)) {
            $settingType = SettingType::BOOLEAN;
            $value = [SettingValue::BOOLEAN => $value];
        } elseif (is_string($value)) {
            $settingType = SettingType::STRING;
            $value = [SettingValue::STRING => $value];
        } elseif (is_int($value)) {
            $settingType = SettingType::INT;
            $value = [SettingValue::INT => $value];
        } elseif (is_double($value)) {
            $settingType = SettingType::DOUBLE;
            $value = [SettingValue::DOUBLE => $value];
        } else {
            $settingType = self::unsupportedTypeToken();
        }

        return [
            self::TYPE => $settingType,
            self::VALUE => $value,
        ];
    }

    /**
     * Returns a token object for indicating an unsupported value coming from flag overrides.
     */
    private static function unsupportedTypeToken(): stdClass
    {
        static $unsupportedTypeToken = null;

        return $unsupportedTypeToken = $unsupportedTypeToken ?? new stdClass();
    }
}
