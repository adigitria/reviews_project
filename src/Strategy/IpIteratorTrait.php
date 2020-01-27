<?php
declare(strict_types=1);

namespace ReviewParser\Strategy;

use ReviewParser\Model\IPIterator;

trait IpIteratorTrait
{
    /**
     * @var IPIterator
     */
    private $IPIterator;

    /**
     * StepByStepIpRound constructor.
     *
     * @param IPIterator $IPIterator
     */
    public function __construct(IPIterator $IPIterator)
    {
        $this->IPIterator = $IPIterator;
    }

    /**
     * @return IPIterator
     */
    public function getIPIterator(): IPIterator
    {
        return $this->IPIterator;
    }
}
