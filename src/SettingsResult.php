<?php

declare(strict_types=1);

namespace ConfigCat;

/**
 * @internal
 */
class SettingsResult
{
    /** @var ?mixed[] */
    public $settings;

    /** @var float */
    public $fetchTime;

    /**
     * @param ?mixed[] $settings
     */
    public function __construct(?array $settings, float $fetchTime)
    {
        $this->settings = $settings;
        $this->fetchTime = $fetchTime;
    }
}
