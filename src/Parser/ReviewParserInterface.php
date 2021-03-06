<?php
declare(strict_types=1);

namespace ReviewParser\Parser;

interface ReviewParserInterface
{
    public function getParserAlias(): string;

    public function getParsingResult();
}
