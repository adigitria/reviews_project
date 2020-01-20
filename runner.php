<?php

include 'autoload.php';

use \ReviewParser\ParserFactory;
use \ReviewParser\Configuration;

$config        = require 'config.php';
$configuration = new Configuration($argv, $config);

$parser = ParserFactory::makeParser($configuration);
$parser->getParsingResult();

