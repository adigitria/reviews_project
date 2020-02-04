<?php
declare(strict_types=1);

namespace ReviewParser\Strategy;

use ReviewParser\Model\IpBlockConfiguration;
use ReviewParser\Model\IPIterator;

interface IpRounderInterface
{
    public function getIPIterator(): IPIterator;

    public function getIpBlockConfiguration(): IpBlockConfiguration;

    public function getResponseTimeout(): int;
}
