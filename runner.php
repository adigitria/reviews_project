<?php

include 'autoload.php';

use \ReviewParser\ParserFactory;
use \ReviewParser\Configuration;

try {
    $config        = require 'config.php';
    $configuration = new Configuration($argv, $config);

    $parser = ParserFactory::makeParser($configuration);
    $parser->getParsingResult();
} catch (\Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
