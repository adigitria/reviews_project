<?php
declare(strict_types=1);

namespace ReviewParser\Strategy;

use ReviewParser\Model\IpBlockConfiguration;
use ReviewParser\Model\IPIterator;

class SmartStepByStepIpRounder extends AbstractIpRounder
{
    private const CONNECTION_TIMOUT_MULTIPLIER = 1.5;

    /**
     * @var int
     */
    private $minimalSizeIpList;

    /**
     * @var int
     */
    private $iterationCount = 0;

    public function __construct(IPIterator $IPIterator, IpBlockConfiguration $ipBlockConfiguration)
    {
        parent::__construct($IPIterator, $ipBlockConfiguration);
        $this->minimalSizeIpList = (int) ceil($IPIterator->count() * 0.1);
    }

    /**
     * @return int
     */
    public function getMinimalSizeIpList(): int
    {
        return $this->minimalSizeIpList;
    }

    /**
     * @return int
     */
    public function getResponseTimeout(): int
    {
        $this->iterationCount++;

        return (int) self::CONNECTION_TIMOUT_MULTIPLIER * $this->ipBlockConfiguration->getConnectionTimeout() * $this->iterationCount;
    }
}
