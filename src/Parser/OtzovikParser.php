<?php
declare(strict_types=1);

namespace ReviewParser\Parser;

use ReviewParser\ParserFactory;

class OtzovikParser extends AbstractReviewParser
{
    public function getParserAlias(): string
    {
        return ParserFactory::OTZOVIK_ALIAS;
    }

    protected function getBaseSiteUrl(): string
    {
        return 'https://otzovik.com';
    }

    protected function pagesParsing()
    {
        $url   = $this->baseSearchUrl . '/%PAGE_NUMBER%/';
        $content = $this->safeGetContentByCurl($url);
    }

    protected function gettingReviewInfoAsHtml()
    {
        // TODO: Implement gettingReviewInfoAsHtml() method.
    }

    protected function gettingReviewsAsJson()
    {
        // TODO: Implement gettingReviewsAsJson() method.
    }
}
