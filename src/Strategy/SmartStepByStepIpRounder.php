<?php
declare(strict_types=1);

namespace ReviewParser\Strategy;

use ReviewParser\Model\IpBlockConfiguration;
use ReviewParser\Model\IPIterator;

class SmartStepByStepIpRounder extends AbstractIpRounder
{
    private const CONNECTION_TIMOUT_MULTIPLIER = 1.15;
    private const COUNT_FAIL_ATTEMPTS          = 3;
    private const PERCENT_MINIMAL_SIZE         = 10;

    /**
     * @var int
     */
    private $minimalSizeIpList;

    /**
     * @var array
     */
    private $countErrors = [];


    public function __construct(IPIterator $IPIterator, IpBlockConfiguration $ipBlockConfiguration)
    {
        parent::__construct($IPIterator, $ipBlockConfiguration);
        $this->minimalSizeIpList = (int) ceil($IPIterator->count() * (self::PERCENT_MINIMAL_SIZE/100));
    }

    public function nextElementByError(string $error): void
    {
        if (isset($this->countErrors[$this->IPIterator->getIp()])) {
            $this->countErrors[$this->IPIterator->getIp()]++;
        } else {
            $this->countErrors[$this->IPIterator->getIp()] = 1;
        }

        if ($this->countErrors[$this->IPIterator->getIp()] > self::COUNT_FAIL_ATTEMPTS
            || $this->IPIterator->count() >= $this->getMinimalSizeIpList()
        ) {
//                echo 'Remove '.$this->IPIterator->getIp().PHP_EOL;
            $this->IPIterator->removeCurrent();
        } else {
            $this->IPIterator->next();
        }
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
        return (int) $this->ipBlockConfiguration->getConnectionTimeout();
    }
}
