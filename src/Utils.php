<?php

declare(strict_types=1);

namespace ConfigCat;

use DateTimeImmutable;
use DateTimeInterface;
use Throwable;

if (!defined('PHP_FLOAT_EPSILON')) {
    define('PHP_FLOAT_EPSILON', 2.2204460492503e-16);
}

/**
 * Contains helper utility operations.
 *
 * @internal
 */
final class Utils
{
    private function __construct()
    {
    }

    /**
     * Polyfill for the `str_starts_with` function introduced in PHP 8.0.
     */
    public static function str_starts_with(string $haystack, string $needle): bool
    {
        // Source: https://github.com/symfony/polyfill/blob/v1.29.0/src/Php80/Php80.php#L96

        return 0 === strncmp($haystack, $needle, \strlen($needle));
    }

    /**
     * Polyfill for the `str_ends_with` function introduced in PHP 8.0.
     */
    public static function str_ends_with(string $haystack, string $needle): bool
    {
        // Source: https://github.com/symfony/polyfill/blob/v1.29.0/src/Php80/Php80.php#L101

        if ('' === $needle || $needle === $haystack) {
            return true;
        }

        if ('' === $haystack) {
            return false;
        }

        $needleLength = \strlen($needle);

        return $needleLength <= \strlen($haystack) && 0 === substr_compare($haystack, $needle, -$needleLength);
    }

    /**
     * Polyfill for the `array_is_list` function introduced in PHP 8.1.
     *
     * @param array<mixed> $array
     */
    public static function array_is_list(array $array): bool
    {
        // Source: https://github.com/symfony/polyfill/blob/v1.29.0/src/Php81/Php81.php#L21

        if ([] === $array || $array === array_values($array)) {
            return true;
        }

        $nextKey = -1;

        foreach ($array as $k => $v) {
            if ($k !== ++$nextKey) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns the string representation of a value.
     *
     * @param mixed $value the value
     *
     * @return string the result string
     */
    public static function getStringRepresentation($value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        try {
            if (self::isConvertibleToString($value)) {
                return (string) $value;
            }
        } catch (Throwable $ex) {
            // intentional no-op
        }

        try {
            return str_replace(["\r\n", "\r", "\n"], ' ', var_export($value, true));
        } catch (Throwable $ex) {
            return '<inconvertible value>';
        }
    }

    /**
     * @param float|int $number
     */
    public static function numberToString($number): string
    {
        if (is_nan($number)) {
            return 'NaN';
        }
        if (is_infinite($number)) {
            return $number > 0 ? 'Infinity' : '-Infinity';
        }
        if (!$number) {
            return '0';
        }

        $abs = abs($number);
        if (1e-6 <= $abs && $abs < 1e21) {
            $exp = 0;
        } else {
            $exp = self::getExponent($abs);
            $number /= pow(10, $exp);
        }

        // NOTE: number_format can't really deal with 17 decimal places,
        // e.g. number_format(0.1, 17, '.', '') results in '0.10000000000000001'.
        // So we need to manually calculate the actual number of significant decimals.
        $decimals = self::getSignificantDecimals($number);

        $str = number_format($number, $decimals, '.', '');
        if ($exp) {
            $str .= ($exp > 0 ? 'e+' : 'e').number_format($exp, 0, '.', '');
        }

        return $str;
    }

    /**
     * @return false|float
     */
    public static function numberFromString(string $str)
    {
        $str = trim($str);

        switch ($str) {
            case 'Infinity':
            case '+Infinity':
                return INF;

            case '-Infinity':
                return -INF;

            case 'NaN':
                return NAN;

            default:
                return filter_var($str, FILTER_VALIDATE_FLOAT);
        }
    }

    /**
     * Returns the Unix timestamp in milliseconds.
     *
     * @return float milliseconds since epoch
     */
    public static function getUnixMilliseconds(): float
    {
        return floor(microtime(true) * 1000);
    }

    public static function dateTimeToUnixTimeSeconds(DateTimeInterface $dateTime): ?float
    {
        $timestamp = (float) $dateTime->format('U\.v');

        // Allow values only between 0001-01-01T00:00:00.000Z and 9999-12-31T23:59:59.999
        return $timestamp < -62135596800 || 253402300800 <= $timestamp ? null : $timestamp;
    }

    public static function dateTimeFromUnixTimeSeconds(float $timestamp): ?DateTimeInterface
    {
        // Allow values only between 0001-01-01T00:00:00.000Z and 9999-12-31T23:59:59.999
        if ($timestamp < -62135596800 || 253402300800 <= $timestamp) {
            return null;
        }

        $timeStampWithMilliseconds = round($timestamp, 3, PHP_ROUND_HALF_UP);
        $dateTime = DateTimeImmutable::createFromFormat('U\\.u', sprintf('%1.6F', $timeStampWithMilliseconds));

        if (!$dateTime) {
            return null;
        }

        return $dateTime;
    }

    public static function formatDateTimeISO(DateTimeInterface $dateTime): string
    {
        $timeOffset = $dateTime->getOffset();

        return $dateTime->format($timeOffset ? 'Y-m-d\\TH:i:s.vP' : 'Y-m-d\\TH:i:s.v\Z');
    }

    /**
     * @param mixed $value
     */
    public static function isStringList($value): bool
    {
        return is_array($value) && !self::array_some($value, function ($value, $key, $i) {
            return $key !== $i || !is_string($value);
        });
    }

    /**
     * @param list<string>               $items
     * @param null|callable(int): string $getOmittedItemsText
     */
    public static function formatStringList(array $items, int $maxCount = 0, ?callable $getOmittedItemsText = null, string $separator = ', '): string
    {
        $count = count($items);
        if (!$count) {
            return '';
        }

        $appendix = '';

        if ($maxCount > 0 && $count > $maxCount) {
            $items = array_slice($items, 0, $maxCount);
            if ($getOmittedItemsText) {
                $appendix = $getOmittedItemsText($count - $maxCount);
            }
        }

        return "'".join("'".$separator."'", $items)."'".$appendix;
    }

    /**
     * @param mixed $value
     */
    private static function isConvertibleToString($value): bool
    {
        // Based on: https://stackoverflow.com/a/5496674
        if (is_array($value)) {
            return false;
        }
        if (is_object($value)) {
            return method_exists($value, '__toString');
        }

        return false !== settype($value, 'string');
    }

    /**
     * @param mixed[]                                         $array   the array to check
     * @param callable(mixed, int|string, int, mixed[]): bool $isMatch a function to execute for each element in the array; it should return a truthy value to indicate the element passes the test, and a falsy value otherwise
     *
     * @return bool `false` unless `$isMatch` returns a truthy value for an array element, in which case true is immediately returned
     */
    private static function array_some(array $array, callable $isMatch): bool
    {
        $i = 0;
        foreach ($array as $key => $value) {
            if ($isMatch($value, $key, $i, $array)) {
                return true;
            }
            ++$i;
        }

        return false;
    }

    /**
     * @param float|int $abs
     */
    private static function getExponent($abs): int
    {
        $exp = log10($abs);
        $ceil = ceil($exp);

        return (int) (abs($exp - $ceil) < PHP_FLOAT_EPSILON ? $ceil : floor($exp));
    }

    // Based on: https://stackoverflow.com/a/31888253/8656352
    /**
     * @param float|int $number
     */
    private static function getSignificantDecimals($number): int
    {
        if (!$number) {
            return 0;
        }

        $number = abs($number);
        $exp = min(0, self::getExponent($number));

        for (; $exp > -17; --$exp) {
            $fracr = round($number, -$exp, PHP_ROUND_HALF_UP);
            // NOTE: PHP_FLOAT_EPSILON is the same as JavaScript's Number.EPSILON
            // (https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Number/EPSILON).
            if (abs($number - $fracr) < $number * 10.0 * PHP_FLOAT_EPSILON) {
                break;
            }
        }

        return min(17, -$exp);
    }
}
