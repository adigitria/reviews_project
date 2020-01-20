<?php
declare(strict_types=1);


namespace ReviewParser;

use ReviewParser\Exception\ParserNotFoundException;
use ReviewParser\Parser\BankiParser;
use ReviewParser\Parser\IrecommendParser;
use ReviewParser\Parser\OtzovikParser;
use ReviewParser\Parser\ReviewParserInterface;

class ParserFactory
{
    public const BANKIRU_ALIAS    = 'banki';
    public const IRECOMMEND_ALIAS = 'irecommend';
    public const OTZOVIK_ALIAS    = 'otzovik';

    public const VALID_ALIASES = [
        self::BANKIRU_ALIAS,
        self::IRECOMMEND_ALIAS,
        self::OTZOVIK_ALIAS,
    ];

    public static function makeParser(Configuration $configuration): ReviewParserInterface
    {
        if ($configuration->getAlias() === self::BANKIRU_ALIAS) {
            return new BankiParser($configuration->getBaseSearchUrl(), $configuration->getCountPages());
        } else if ($configuration->getAlias() === self::IRECOMMEND_ALIAS) {
            return new IrecommendParser($configuration->getBaseSearchUrl(), $configuration->getCountPages(), $configuration->getHeaders($configuration->getAlias()));
        } else if ($configuration->getAlias() === self::OTZOVIK_ALIAS) {
            return new OtzovikParser($configuration->getBaseSearchUrl(), $configuration->getCountPages());
        } else {
            throw new ParserNotFoundException(ParserNotFoundException::ERROR_MESSAGE);
        }
    }
}
