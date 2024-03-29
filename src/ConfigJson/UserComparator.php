<?php

declare(strict_types=1);

namespace ConfigCat\ConfigJson;

/**
 * User Object attribute comparison operator used during the evaluation process.
 */
final class UserComparator
{
    /** IS ONE OF (cleartext) - Checks whether the comparison attribute is equal to any of the comparison values. */
    public const TEXT_IS_ONE_OF = 0;

    /** IS NOT ONE OF (cleartext) - Checks whether the comparison attribute is not equal to any of the comparison values. */
    public const TEXT_IS_NOT_ONE_OF = 1;

    /** CONTAINS ANY OF (cleartext) - Checks whether the comparison attribute contains any comparison values as a substring. */
    public const TEXT_CONTAINS_ANY_OF = 2;

    /** NOT CONTAINS ANY OF (cleartext) - Checks whether the comparison attribute does not contain any comparison values as a substring. */
    public const TEXT_NOT_CONTAINS_ANY_OF = 3;

    /** IS ONE OF (semver) - Checks whether the comparison attribute interpreted as a semantic version is equal to any of the comparison values. */
    public const SEMVER_IS_ONE_OF = 4;

    /** IS NOT ONE OF (semver) - Checks whether the comparison attribute interpreted as a semantic version is not equal to any of the comparison values. */
    public const SEMVER_IS_NOT_ONE_OF = 5;

    /** &lt; (semver) - Checks whether the comparison attribute interpreted as a semantic version is less than the comparison value. */
    public const SEMVER_LESS = 6;

    /** &lt;= (semver) - Checks whether the comparison attribute interpreted as a semantic version is less than or equal to the comparison value. */
    public const SEMVER_LESS_OR_EQUALS = 7;

    /** &gt; (semver) - Checks whether the comparison attribute interpreted as a semantic version is greater than the comparison value. */
    public const SEMVER_GREATER = 8;

    /** &gt;= (semver) - Checks whether the comparison attribute interpreted as a semantic version is greater than or equal to the comparison value. */
    public const SEMVER_GREATER_OR_EQUALS = 9;

    /** = (number) - Checks whether the comparison attribute interpreted as a decimal number is equal to the comparison value. */
    public const NUMBER_EQUALS = 10;

    /** != (number) - Checks whether the comparison attribute interpreted as a decimal number is not equal to the comparison value. */
    public const NUMBER_NOT_EQUALS = 11;

    /** &lt; (number) - Checks whether the comparison attribute interpreted as a decimal number is less than the comparison value. */
    public const NUMBER_LESS = 12;

    /** &lt;= (number) - Checks whether the comparison attribute interpreted as a decimal number is less than or equal to the comparison value. */
    public const NUMBER_LESS_OR_EQUALS = 13;

    /** &gt; (number) - Checks whether the comparison attribute interpreted as a decimal number is greater than the comparison value. */
    public const NUMBER_GREATER = 14;

    /** &gt;= (number) - Checks whether the comparison attribute interpreted as a decimal number is greater than or equal to the comparison value. */
    public const NUMBER_GREATER_OR_EQUALS = 15;

    /** IS ONE OF (hashed) - Checks whether the comparison attribute is equal to any of the comparison values (where the comparison is performed using the salted SHA256 hashes of the values). */
    public const SENSITIVE_TEXT_IS_ONE_OF = 16;

    /** IS NOT ONE OF (hashed) - Checks whether the comparison attribute is not equal to any of the comparison values (where the comparison is performed using the salted SHA256 hashes of the values). */
    public const SENSITIVE_TEXT_IS_NOT_ONE_OF = 17;

    /** BEFORE (UTC datetime) - Checks whether the comparison attribute interpreted as the seconds elapsed since <see href="https://en.wikipedia.org/wiki/Unix_time">Unix Epoch</see> is less than the comparison value. */
    public const DATETIME_BEFORE = 18;

    /** AFTER (UTC datetime) - Checks whether the comparison attribute interpreted as the seconds elapsed since <see href="https://en.wikipedia.org/wiki/Unix_time">Unix Epoch</see> is greater than the comparison value. */
    public const DATETIME_AFTER = 19;

    /** EQUALS (hashed) - Checks whether the comparison attribute is equal to the comparison value (where the comparison is performed using the salted SHA256 hashes of the values). */
    public const SENSITIVE_TEXT_EQUALS = 20;

    /** NOT EQUALS (hashed) - Checks whether the comparison attribute is not equal to the comparison value (where the comparison is performed using the salted SHA256 hashes of the values). */
    public const SENSITIVE_TEXT_NOT_EQUALS = 21;

    /** STARTS WITH ANY OF (hashed) - Checks whether the comparison attribute starts with any of the comparison values (where the comparison is performed using the salted SHA256 hashes of the values). */
    public const SENSITIVE_TEXT_STARTS_WITH_ANY_OF = 22;

    /** NOT STARTS WITH ANY OF (hashed) - Checks whether the comparison attribute does not start with any of the comparison values (where the comparison is performed using the salted SHA256 hashes of the values). */
    public const SENSITIVE_TEXT_NOT_STARTS_WITH_ANY_OF = 23;

    /** ENDS WITH ANY OF (hashed) - Checks whether the comparison attribute ends with any of the comparison values (where the comparison is performed using the salted SHA256 hashes of the values). */
    public const SENSITIVE_TEXT_ENDS_WITH_ANY_OF = 24;

    /** NOT ENDS WITH ANY OF (hashed) - Checks whether the comparison attribute does not end with any of the comparison values (where the comparison is performed using the salted SHA256 hashes of the values). */
    public const SENSITIVE_TEXT_NOT_ENDS_WITH_ANY_OF = 25;

    /** ARRAY CONTAINS ANY OF (hashed) - Checks whether the comparison attribute interpreted as a comma-separated list contains any of the comparison values (where the comparison is performed using the salted SHA256 hashes of the values). */
    public const SENSITIVE_ARRAY_CONTAINS_ANY_OF = 26;

    /** ARRAY NOT CONTAINS ANY OF (hashed) - Checks whether the comparison attribute interpreted as a comma-separated list does not contain any of the comparison values (where the comparison is performed using the salted SHA256 hashes of the values). */
    public const SENSITIVE_ARRAY_NOT_CONTAINS_ANY_OF = 27;

    /** EQUALS (cleartext) - Checks whether the comparison attribute is equal to the comparison value. */
    public const TEXT_EQUALS = 28;

    /** NOT EQUALS (cleartext) - Checks whether the comparison attribute is not equal to the comparison value. */
    public const TEXT_NOT_EQUALS = 29;

    /** STARTS WITH ANY OF (cleartext) - Checks whether the comparison attribute starts with any of the comparison values. */
    public const TEXT_STARTS_WITH_ANY_OF = 30;

    /** NOT STARTS WITH ANY OF (cleartext) - Checks whether the comparison attribute does not start with any of the comparison values. */
    public const TEXT_NOT_STARTS_WITH_ANY_OF = 31;

    /** ENDS WITH ANY OF (cleartext) - Checks whether the comparison attribute ends with any of the comparison values. */
    public const TEXT_ENDS_WITH_ANY_OF = 32;

    /** NOT ENDS WITH ANY OF (cleartext) - Checks whether the comparison attribute does not end with any of the comparison values. */
    public const TEXT_NOT_ENDS_WITH_ANY_OF = 33;

    /** ARRAY CONTAINS ANY OF (cleartext) - Checks whether the comparison attribute interpreted as a comma-separated list contains any of the comparison values. */
    public const ARRAY_CONTAINS_ANY_OF = 34;

    /** ARRAY NOT CONTAINS ANY OF (cleartext) - Checks whether the comparison attribute interpreted as a comma-separated list does not contain any of the comparison values. */
    public const ARRAY_NOT_CONTAINS_ANY_OF = 35;

    private function __construct()
    {
    }

    /**
     * @internal
     */
    public static function getValidOrDefault(int $value, ?int $defaultValue = null): ?int
    {
        return self::TEXT_IS_ONE_OF <= $value && $value <= self::ARRAY_NOT_CONTAINS_ANY_OF ? $value : $defaultValue;
    }
}
