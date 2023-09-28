<?php

declare(strict_types=1);

namespace ConfigCat;

/**
 * @internal
 */
class SettingsResult
{
    /** @var array */
    public $settings;
    /** @var float */
    public $fetchTime;

    public function __construct(?array $settings, float $fetchTime)
    {
        $this->settings = $settings;
        $this->fetchTime = $fetchTime;
    }
}
