<?php

declare(strict_types=1);

namespace ConfigCat;

/**
 * Represents a simple key-value pair.
 */
class Pair
{
    /** @var string The key. */
    private $key;

    /** @var mixed The value. */
    private $value;

    /**
     * Creates a new Pair.
     *
     * @param string $key   the key
     * @param mixed  $value The value:
     */
    public function __construct(string $key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Gets the key.
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Gets the value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
