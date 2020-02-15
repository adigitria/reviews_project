<?php
declare(strict_types=1);

namespace ReviewParser\Parser;

use ReviewParser\Exception\ProblemWithDownloadPageException;
use ReviewParser\Helper\ArchiveHelper;
use ReviewParser\Helper\Logger;
use ReviewParser\Helper\RequestHelper;

abstract class AbstractReviewParser implements ReviewParserInterface
{
    /**
     * @var RequestHelper
     */
    protected $requestHelper;

    /**
     * @var string
     */
    protected $baseSearchUrl;

    /**
     * @var int
     */
    protected $finalPageNumber;

    /**
     * @var ArchiveHelper
     */
    protected $archiveHelper;

    /**
     * @var Logger
     */
    protected $connectionLog;

    /**
     * @var int
     */
    protected $startPageNumber;

    /**
     * BankiParser constructor.
     *
     * @param RequestHelper $requestHelper
     * @param string        $baseSearchUrl
     * @param int           $startPageNumber
     * @param int           $finalPageNumber
     */
    public function __construct(RequestHelper $requestHelper, string $baseSearchUrl, int $startPageNumber, int $finalPageNumber)
    {
        $this->requestHelper   = $requestHelper;
        $this->baseSearchUrl   = $baseSearchUrl;
        $this->startPageNumber = $startPageNumber;
        $this->finalPageNumber = $finalPageNumber;

        $this->archiveHelper = new ArchiveHelper(
            $this->getParserAlias(), [
            'pages'   => $this->getPagesHtmlDir(),
            'reviews' => $this->getReviewsHtmlDir(),
            'results' => $this->getResultsDir(),
        ]
        );
    }

    public function getParsingResult(): void
    {
        $this->archiveHelper->movePreviousToArchive();

        try {
            $this->pagesParsing();
        } catch (ProblemWithDownloadPageException $exception) {
            $this->connectionLog->addErrorMessage($exception->getUrl());
            echo $exception->getMessage() . PHP_EOL;
        }

        $this->gettingReviewInfoAsHtml();
        $this->gettingReviewsAsJson();
    }

    /**
     * @param Logger $connectionLog
     */
    public function setConnectionLog(Logger $connectionLog): void
    {
        $this->connectionLog = $connectionLog;
    }

    protected function safeGetContentByCurl(string $url): string
    {
        [$error, $content] = $this->requestHelper->makeRequest($url,$this->connectionLog);

        if ($error !== '') {
            throw new ProblemWithDownloadPageException($error, $url);
        }

        echo 'Download page - ' . $url . PHP_EOL;
        $this->connectionLog->addInfoMessage($url);

        return $content;
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

    protected function getResultFileName(): string
    {
        return $this->getParserAlias().'_reviews_'.$this->startPageNumber.'_'.$this->finalPageNumber.'.json';
    }

    abstract protected function getBaseSiteUrl(): string;

    abstract protected function pagesParsing();

    abstract protected function gettingReviewInfoAsHtml();

    abstract protected function gettingReviewsAsJson();
}
