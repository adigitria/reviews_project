<?php
declare(strict_types=1);


namespace ReviewParser\Parser;


use ReviewParser\Configuration;

abstract class AbstractReviewParser implements ReviewParserInterface
{
    protected $baseSearchUrl;

    protected $countPages;

    /**
     * @var array
     */
    protected $headers;

    /**
     * BankiParser constructor.
     *
     * @param string $baseSearchUrl
     * @param int    $countPages
     * @param array  $headers
     */
    public function __construct(string $baseSearchUrl, int $countPages, array $headers = [])
    {
        $this->baseSearchUrl = $baseSearchUrl;
        $this->countPages    = $countPages;
        $this->headers       = $headers;
    }

    public function getParsingResult()
    {
        $this->pageParsing();
        $this->gettingReviewInfoAsHtml();
        $this->gettingReviewsAsJson();
    }

    protected function getPagesHtmlDir(): string
    {
        return 'pages/' . $this->getParserAlias();
    }

    protected function getReviewsHtmlDir(): string
    {
        return 'reviews/' . $this->getParserAlias();
    }

    protected function getResultsDir(): string
    {
        return 'results/' . $this->getParserAlias();
    }

    abstract protected function getBaseSiteUrl(): string;

    abstract protected function pageParsing();

    abstract protected function gettingReviewInfoAsHtml();

    abstract protected function gettingReviewsAsJson();
}
