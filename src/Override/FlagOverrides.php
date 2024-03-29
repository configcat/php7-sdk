<?php

declare(strict_types=1);

namespace ConfigCat\Override;

use InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Describes feature flag & setting overrides.
 */
class FlagOverrides implements LoggerAwareInterface
{
    /** @var int */
    private $behaviour;

    /** @var OverrideDataSource */
    private $dataSource;

    /**
     * Constructs the feature flag overrides.
     *
     * @param OverrideDataSource $dataSource the data source of the feature flag overrides
     * @param int                $behaviour  it can be used to set preference on whether the local values should
     *                                       override the remote values, or use local values only when a remote value doesn't exist,
     *                                       or use it for local only mode
     */
    public function __construct(OverrideDataSource $dataSource, int $behaviour)
    {
        if (!OverrideBehaviour::isValid($behaviour)) {
            throw new InvalidArgumentException('The behaviour argument is not valid.');
        }

        $this->behaviour = $behaviour;
        $this->dataSource = $dataSource;
    }

    /**
     * Gets the override behaviour.
     *
     * @return int the override behaviour
     */
    public function getBehaviour(): int
    {
        return $this->behaviour;
    }

    /**
     * Gets the override data source.
     *
     * @return OverrideDataSource the override data source
     */
    public function getDataSource(): OverrideDataSource
    {
        return $this->dataSource;
    }

    /**
     * Sets the logger.
     *
     * @param LoggerInterface $logger the logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->dataSource->setLogger($logger);
    }
}
