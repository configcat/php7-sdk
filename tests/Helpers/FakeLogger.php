<?php

namespace ConfigCat\Tests\Helpers;

use ConfigCat\Log\LogLevel;
use Psr\Log\LoggerInterface;

class FakeLogger implements LoggerInterface
{
    public $events = [];

    public static function formatMessage(array $event)
    {
        $context = $event['context'];
        $context['level'] = LogLevel::asString($event['level']);

        $final = self::interpolate('{level} [{event_id}] '.$event['message'], $context);

        if (isset($context['exception'])) {
            $final .= PHP_EOL.$context['exception'];
        }

        return $final;
    }

    public function emergency($message, array $context = []): void
    {
        self::logMsg(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = []): void
    {
        self::logMsg(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = []): void
    {
        self::logMsg(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = []): void
    {
        self::logMsg(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = []): void
    {
        self::logMsg(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = []): void
    {
        self::logMsg(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = []): void
    {
        self::logMsg(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = []): void
    {
        self::logMsg(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = []): void
    {
        // Do nothing, only the leveled methods should be used.
    }

    /**
     * @param mixed[] $context
     * @param mixed   $message
     */
    private function logMsg(int $level, $message, array $context = []): void
    {
        $context['event_id'] = $context['event_id'] ?? 0;
        $this->events[] = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];
    }

    /**
     * @param mixed[] $context
     * @param mixed   $message
     */
    private static function interpolate($message, array $context = []): string
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{'.$key.'}'] = $val;
            }
        }

        return strtr((string) $message, $replace);
    }
}
