<?php
declare(strict_types=1);

namespace ReviewParser\Strategy;

use ReviewParser\Model\IpBlockConfiguration;
use ReviewParser\Model\IPIterator;

class SmartStepByStepIpRounder extends AbstractIpRounder
{
    private const CONNECTION_TIMOUT_MULTIPLIER = 1.5;
    private const COUNT_FAIL_ATTEMPTS          = 3;

    /**
     * @var int
     */
    private $minimalSizeIpList;

    private $countErrors = [];


    public function __construct(IPIterator $IPIterator, IpBlockConfiguration $ipBlockConfiguration)
    {
        parent::__construct($IPIterator, $ipBlockConfiguration);
        $this->minimalSizeIpList = (int) ceil($IPIterator->count() * 0.2);
    }

    public function nextElementByError(string $error): void
    {
        if ($error !== '') {
            if(isset($this->countErrors[$this->IPIterator->getIp()])){
                $this->countErrors[$this->IPIterator->getIp()]++;
            }else{
                $this->countErrors[$this->IPIterator->getIp()] = 1;
            }

            if ($this->countErrors[$this->IPIterator->getIp()] > self::COUNT_FAIL_ATTEMPTS
                || $this->IPIterator->count() > $this->getMinimalSizeIpList()
            ) {
                $this->IPIterator->removeCurrent();
            } else {
                $this->IPIterator->next();
            }
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
        return (int) self::CONNECTION_TIMOUT_MULTIPLIER
            * $this->ipBlockConfiguration->getConnectionTimeout()
            * $this->getIPIterator()->getIterationCount();
    }
}
