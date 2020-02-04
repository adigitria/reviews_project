<?php
declare(strict_types=1);


namespace ReviewParser\Exception;

use \RuntimeException;

class IpRounderStrategyNotFoundException extends RuntimeException
{
    public const ERROR_MESSAGE = 'IpRounderStrategy not found';
}
