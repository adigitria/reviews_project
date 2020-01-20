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

    public static function makeParser(string $alias, string $baseSearchUrl, int $countPages): ReviewParserInterface
    {
        if ($alias === self::BANKIRU_ALIAS) {
            return new BankiParser();
        } else if ($alias === self::IRECOMMEND_ALIAS) {
            return new IrecommendParser();
        } else if ($alias === self::OTZOVIK_ALIAS) {
            return new OtzovikParser();
        } else {
            throw new ParserNotFoundException(ParserNotFoundException::ERROR_MESSAGE);
        }
    }
}
