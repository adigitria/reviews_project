<?php
declare(strict_types=1);

namespace ReviewParser\Strategy;

use ReviewParser\Model\IPIterator;

class DefaultIpRound implements IpRoundInterface
{
    use IpIteratorTrait;
}
