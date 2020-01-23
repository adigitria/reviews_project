<?php
declare(strict_types=1);

namespace ReviewParser;

use ReviewParser\Exception\ParserNotFoundException;
use ReviewParser\Helper\Logger;
use ReviewParser\Helper\RequestHelper;
use ReviewParser\Parser\BankiParser;
use ReviewParser\Parser\IrecommendParser;
use ReviewParser\Parser\OtzovikParser;
use ReviewParser\Parser\ReviewParserInterface;

class ParserFactory
{
    public const BANKIRU_ALIAS = 'banki';
    public const IRECOMMEND_ALIAS = 'irecommend';
    public const OTZOVIK_ALIAS = 'otzovik';

    public static function makeParser(Configuration $configuration): ReviewParserInterface
    {
        $requestHelper = new RequestHelper($configuration->getRequestHeaders($configuration->getAlias()));

        if ($configuration->getAlias() === self::BANKIRU_ALIAS) {
            $parser = new BankiParser($requestHelper, $configuration->getBaseSearchUrl(), $configuration->getCountPages());
        } else if ($configuration->getAlias() === self::IRECOMMEND_ALIAS) {
            $parser = new IrecommendParser($requestHelper, $configuration->getBaseSearchUrl(), $configuration->getCountPages());
        } else if ($configuration->getAlias() === self::OTZOVIK_ALIAS) {
            $parser = new OtzovikParser($requestHelper, $configuration->getBaseSearchUrl(), $configuration->getCountPages());
        } else {
            throw new ParserNotFoundException(ParserNotFoundException::ERROR_MESSAGE);
        }

        $parser->setConnectionLog(new Logger('logs/connection.log'));

        return $parser;
    }
}
