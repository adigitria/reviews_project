<?php
declare(strict_types=1);

namespace ReviewParser\Exception;

class ParserNotFoundException extends \RuntimeException
{
    public const ERROR_MESSAGE = 'Parser Not Found for this alias';
}
