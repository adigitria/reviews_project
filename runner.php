<?php

include 'autoload.php';
include 'config.php';

use \ReviewParser\ParserFactory;

if (isset($argv[1], $argv[2], $argv[3])) {
//    $alias         = $argv[1];
//    $baseSearchUrl = $argv[2];
//    $countPages    = (int) $argv[3];

    $alias         = ParserFactory::BANKIRU_ALIAS;
    $baseSearchUrl = 'https://www.banki.ru/telecom/responses/company/tele2/';
    $countPages    = 3;

    $parser = ParserFactory::makeParser($alias, $baseSearchUrl, $countPages);
    $parser->getParsingResult();
} else {
    echo 'Problems with args' . PHP_EOL;
}
