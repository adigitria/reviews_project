<?php
declare(strict_types=1);

namespace ReviewParser\Strategy;

use ReviewParser\Model\IpBlockConfiguration;
use ReviewParser\Model\IPIterator;

trait IpIteratorTrait
{
    /**
     * @var IPIterator
     */
    private $IPIterator;

    /**
     * @var IpBlockConfiguration
     */
    private $ipBlockConfiguration;

    /**
     * StepByStepIpRound constructor.
     *
     * @param IPIterator           $IPIterator
     * @param IpBlockConfiguration $ipBlockConfiguration
     */
    public function __construct(IPIterator $IPIterator, IpBlockConfiguration $ipBlockConfiguration)
    {
        $this->IPIterator = $IPIterator;
        $this->ipBlockConfiguration = $ipBlockConfiguration;
    }

    /**
     * @return IPIterator
     */
    public function getIPIterator(): IPIterator
    {
        return $this->IPIterator;
    }

    /**
     * @return IpBlockConfiguration
     */
    public function getIpBlockConfiguration(): IpBlockConfiguration
    {
        return $this->ipBlockConfiguration;
    }


}
