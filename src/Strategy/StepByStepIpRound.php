<?php
declare(strict_types=1);

namespace ReviewParser\Strategy;

class StepByStepIpRound implements IpRoundInterface
{
    use IpIteratorTrait;
}
