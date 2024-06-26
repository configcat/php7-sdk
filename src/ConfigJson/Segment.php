<?php

declare(strict_types=1);

namespace ConfigCat\ConfigJson;

use ConfigCat\Utils;
use UnexpectedValueException;

/**
 * Represents the JSON keys of a segment.
 */
final class Segment
{
    public const NAME = 'n';
    public const CONDITIONS = 'r';

    private function __construct()
    {
    }

    /**
     * @param mixed $segments
     *
     * @throws UnexpectedValueException
     *
     * @return list<array<string, mixed>>
     *
     * @internal
     */
    public static function ensureList($segments): array
    {
        if (!is_array($segments) || !Utils::array_is_list($segments)) {
            throw new UnexpectedValueException('Segment list is invalid.');
        }

        return $segments;
    }

    /**
     * @param mixed $segment
     *
     * @throws UnexpectedValueException
     *
     * @return array<string, mixed>
     *
     * @internal
     */
    public static function ensure($segment): array
    {
        if (!is_array($segment)) {
            throw new UnexpectedValueException('Segment is missing or invalid.');
        }

        return $segment;
    }

    /**
     * @return callable(array<string, mixed>, string&): array<string, mixed>
     */
    public static function conditionAccessor(): callable
    {
        return function (array $condition, string &$conditionType): array {
            $conditionType = ConditionContainer::USER_CONDITION;

            return $condition;
        };
    }
}
