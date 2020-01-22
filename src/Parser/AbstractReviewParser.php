<?php
declare(strict_types=1);

namespace ReviewParser\Parser;

use ReviewParser\Exception\ProblemWithDownloadPageException;
use ReviewParser\Helper\ArchiveHelper;

abstract class AbstractReviewParser implements ReviewParserInterface
{
    /**
     * @var string
     */
    protected $baseSearchUrl;

    /**
     * @var int
     */
    protected $countPages;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var ArchiveHelper
     */
    protected $archiveHelper;

    /**
     * BankiParser constructor.
     *
     * @param string $baseSearchUrl
     * @param int $countPages
     * @param array $headers
     */
    public function __construct(string $baseSearchUrl, int $countPages, array $headers = [])
    {
        $this->baseSearchUrl = $baseSearchUrl;
        $this->countPages = $countPages;
        $this->headers = $headers;

        $this->archiveHelper = new ArchiveHelper($this->getParserAlias(), [
            'pages' => $this->getPagesHtmlDir(),
            'reviews' => $this->getReviewsHtmlDir(),
            'results' => $this->getResultsDir(),
        ]);
    }

    public function getParsingResult()
    {
        $this->archiveHelper->movePreviousToArchive();

        try {
            $this->pagesParsing();
        } catch (ProblemWithDownloadPageException $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }

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

    protected function safeGetContents(string $url, $use_include_path = false, $context = null): string
    {
        $content = file_get_contents($url, $use_include_path, $context);

        if ($content === false) {
            throw new ProblemWithDownloadPageException('Could not get an answer from ' . $url);
        } elseif (!in_array('HTTP/1.1 200 OK', $http_response_header)) {
            $headers = implode(';', $http_response_header);
            throw new ProblemWithDownloadPageException('Got no 200 code. Response headers: ' . $headers);
        }

        return $content;
    }

    abstract protected function getBaseSiteUrl(): string;

    abstract protected function pagesParsing();

    abstract protected function gettingReviewInfoAsHtml();

    abstract protected function gettingReviewsAsJson();
}
