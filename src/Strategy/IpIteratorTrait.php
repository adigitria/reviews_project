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
    protected $IPIterator;

    /**
     * @var IpBlockConfiguration
     */
    protected $ipBlockConfiguration;

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
