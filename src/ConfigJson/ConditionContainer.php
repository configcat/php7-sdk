<?php

declare(strict_types=1);

namespace ConfigCat\ConfigJson;

use ConfigCat\Utils;
use UnexpectedValueException;

/**
 * Represents the JSON keys of a condition.
 */
final class ConditionContainer
{
    public const USER_CONDITION = 'u';
    public const PREREQUISITE_FLAG_CONDITION = 'p';
    public const SEGMENT_CONDITION = 's';

    private function __construct()
    {
    }

    /**
     * @param mixed $conditions
     *
     * @throws UnexpectedValueException
     *
     * @return list<array<string, mixed>>
     *
     * @internal
     */
    public static function ensureList($conditions): array
    {
        if (!is_array($conditions) || !Utils::array_is_list($conditions)) {
            throw new UnexpectedValueException('Condition list is invalid.');
        }

        return $conditions;
    }

    /**
     * @param mixed $condition
     *
     * @throws UnexpectedValueException
     *
     * @return array<string, mixed>
     *
     * @internal
     */
    public static function ensure($condition): array
    {
        if (!is_array($condition)) {
            throw new UnexpectedValueException('Condition is missing or invalid.');
        }

        return $condition;
    }

    /**
     * @return callable(array<string, mixed>, string&): (null|array<string, mixed>)
     */
    public static function conditionAccessor(): callable
    {
        return function (array $conditionContainer, string &$conditionType): ?array {
            return $conditionContainer[$conditionType = ConditionContainer::USER_CONDITION]
                ?? $conditionContainer[$conditionType = ConditionContainer::PREREQUISITE_FLAG_CONDITION]
                ?? $conditionContainer[$conditionType = ConditionContainer::SEGMENT_CONDITION]
                ?? null;
        };
    }
}
