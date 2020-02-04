<?php
declare(strict_types=1);

namespace ReviewParser\Strategy;

use ReviewParser\Model\IpBlockConfiguration;
use ReviewParser\Model\IPIterator;

abstract class AbstractIpRounder implements IpRounderInterface
{
    use IpIteratorTrait;

    /**
     * AbstractIpRounder constructor.
     *
     * @param IPIterator           $IPIterator
     * @param IpBlockConfiguration $ipBlockConfiguration
     */
    public function __construct(IPIterator $IPIterator, IpBlockConfiguration $ipBlockConfiguration)
    {
        $this->IPIterator           = $IPIterator;
        $this->ipBlockConfiguration = $ipBlockConfiguration;
    }

    public function getResponseTimeout(): int
    {
        return $this->ipBlockConfiguration->getConnectionTimeout();
    }

    public function nextElementByError(string $error): void
    {
        $this->IPIterator->next();
    }
}
