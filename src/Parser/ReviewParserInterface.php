<?php
declare(strict_types=1);


namespace ReviewParser\Parser;


use ReviewParser\Configuration;

interface ReviewParserInterface
{
    public function getParserAlias(): string;

    public function getParsingResult();
}
