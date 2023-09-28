<?php

declare(strict_types=1);

namespace ConfigCat\Override;

use ConfigCat\Attributes\SettingAttributes;

/**
 * Describes an array override data source.
 * @package ConfigCat
 */
class ArrayDataSource extends OverrideDataSource
{
    /** @var array */
    private $overrides;

    /**
     * Constructs an array data source.
     * @param $overrides array The array that contains the overrides.
     */
    public function __construct(array $overrides)
    {
        $this->overrides = $overrides;
    }

    /**
     * Gets the overrides.
     * @return array The overrides.
     */
    public function getOverrides(): array
    {
        $result = [];
        foreach ($this->overrides as $key => $value) {
            $result[$key] = [
                SettingAttributes::VALUE => $value
            ];
        }
        return $result;
    }
}
