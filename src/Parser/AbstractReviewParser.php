<?php
declare(strict_types=1);

namespace ReviewParser\Parser;

use \ZipArchive;

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
        $this->moveDirectoryToArchive($this->getPagesHtmlDir(), 'archive/pages/');
        $this->moveDirectoryToArchive($this->getReviewsHtmlDir(), 'archive/reviews/');
        $this->moveDirectoryToArchive($this->getResultsDir(), 'archive/results/');

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

    protected function moveDirectoryToArchive(string $compressDirectory, string $archiveDirectory)
    {
        $compressDirectory .= '/';
        $currentFilesCount = count(scandir($archiveDirectory)) - 3;
        $archiveFile       = $archiveDirectory . $this->getParserAlias() . '_' . ($currentFilesCount + 1) . '.zip';
        $compressDirectoryItterator = glob($compressDirectory . '*');
        if(count($compressDirectoryItterator) > 0){
            $zip = new ZipArchive();
            $res = $zip->open($archiveFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            if ($res === true) {
                foreach ($compressDirectoryItterator as $file) {
                    if (!strpos($file, '.gitempty')) {
                        $zip->addFile($file, basename($file));
                    }
                }
                $zip->close();

                foreach ($compressDirectoryItterator as $file) {
                    if (!strpos($file, '.gitempty')) {
                        unlink($file);
                    }
                }
            } else {
                throw new \RuntimeException('Failed to create to zip. Error: ' . $res);
            }
        }
    }
}
