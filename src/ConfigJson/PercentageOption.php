<?php

declare(strict_types=1);

namespace ConfigCat\ConfigJson;

use UnexpectedValueException;

/**
 * Represents the JSON keys of a percentage option.
 */
final class PercentageOption extends SettingValueContainer
{
    public const PERCENTAGE = 'p';

    protected function __construct()
    {
    }

    /**
     * @param mixed $percentageOptions
     *
     * @throws UnexpectedValueException
     *
     * @return list<array<string, mixed>>
     *
     * @internal
     */
    public static function ensureList($percentageOptions): array
    {
        if (!is_array($percentageOptions) || !array_is_list($percentageOptions)) {
            throw new UnexpectedValueException('Percentage option list is invalid.');
        }

        return $percentageOptions;
    }

    /**
     * @param mixed $percentageOption
     *
     * @throws UnexpectedValueException
     *
     * @return array<string, mixed>
     *
     * @internal
     */
    public static function ensure($percentageOption): array
    {
        if (!is_array($percentageOption)) {
            throw new UnexpectedValueException('Percentage option is missing or invalid.');
        }

        return $percentageOption;
    }
}
