<?php

declare(strict_types=1);

namespace ConfigCat\Log;

/**
 * Determines the current log level of the ConfigCat SDK.
 */
class LogLevel
{
    public const DEBUG = 10;
    public const INFO = 20;
    public const NOTICE = 30;
    public const WARNING = 40;
    public const ERROR = 50;
    public const CRITICAL = 60;
    public const ALERT = 70;
    public const EMERGENCY = 80;
    public const NO_LOG = 90;

    public static function isValid(int $level): bool
    {
        return self::DEBUG == $level
            || self::INFO == $level
            || self::NOTICE == $level
            || self::WARNING == $level
            || self::ERROR == $level
            || self::CRITICAL == $level
            || self::ALERT == $level
            || self::EMERGENCY == $level
            || self::NO_LOG == $level;
    }

    public static function asString(int $level): string
    {
        switch ($level) {
            case self::DEBUG:
                return 'DEBUG';

            case self::INFO:
                return 'INFO';

            case self::NOTICE:
                return 'NOTICE';

            case self::WARNING:
                return 'WARNING';

            case self::ERROR:
                return 'ERROR';

            case self::CRITICAL:
                return 'CRITICAL';

            case self::ALERT:
                return 'ALERT';

            case self::EMERGENCY:
                return 'EMERGENCY';

            case self::NO_LOG:
                return 'NO_LOG';

            default:
                return '';
        }
    }
}
