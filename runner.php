<?php

use \ReviewParser\ParserFactory;

if (isset($argv[1], $argv[2], $argv[3])) {
    $alias         = $argv[1];
    $baseSearchUrl = $argv[2];
    $countPages    = (int) $argv[3];

    $parser = ParserFactory::makeParser($alias, $baseSearchUrl, $countPages);
    $parser->getParsingResult();
} else {

}
