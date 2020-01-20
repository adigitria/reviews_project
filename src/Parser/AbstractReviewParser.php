<?php
declare(strict_types=1);


namespace ReviewParser\Parser;


abstract class AbstractReviewParser implements ReviewParserInterface
{
    protected function getPagesHtmlDir(): string
    {
        return 'pages/'.$this->getParserAlias();
    }

    protected function getReviewsHtmlDir(): string
    {
        return 'reviews/'.$this->getParserAlias();
    }

    protected function getResultsDir(): string
    {
        return 'results/'.$this->getParserAlias();
    }

    abstract protected function getBaseSiteUrl(): string;
}
