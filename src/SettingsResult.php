<?php

declare(strict_types=1);

namespace ConfigCat;

/**
 * @internal
 */
final class SettingsResult
{
    /** @var array<string, mixed> */
    public $settings;

    /** @var float */
    public $fetchTime;

    /** @var bool */
    public $hasConfigJson;

    /**
     * @param array<string, mixed> $settings
     */
    public function __construct(array $settings, float $fetchTime, bool $hasConfigJson)
    {
        $this->settings = $settings;
        $this->fetchTime = $fetchTime;
        $this->hasConfigJson = $hasConfigJson;
    }
 }
