<?php
declare(strict_types=1);

namespace ReviewParser\Parser;

use \ZipArchive;

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
        $this->movePreviousToArchive();

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

    protected function movePreviousToArchive(): void
    {
        $archiveDirectory = 'archive/';
        $archiveFile      = $archiveDirectory . $this->getParserAlias() . '_' . date('Ymd_His') . '.zip';

        $compressDirs = [
            'pages'   => $this->getPagesHtmlDir(),
            'reviews' => $this->getReviewsHtmlDir(),
            'results' => $this->getResultsDir(),
        ];

        $this->archiveDirectories($archiveFile, $compressDirs);
        $this->removeDirectoriesContent($compressDirs);
    }

    /**
     * @param string $archiveFile
     * @param array  $compressDirs
     */
    protected function archiveDirectories(string $archiveFile, array $compressDirs): void
    {
        $zip = new ZipArchive();
        $res = $zip->open($archiveFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($res === true) {
            foreach ($compressDirs as $type => $compressDir) {
                $compressDirectoryItterator = glob($compressDir . '/*');
                $zip->addEmptyDir($type);
                foreach ($compressDirectoryItterator as $file) {
                    if (!strpos($file, '.gitempty')) {
                        $zip->addFile($file, $type.'/'.basename($file));
                    }
                }
            }
            $zip->close();
        } else {
            throw new \RuntimeException('Failed to create to zip. Error: ' . $res);
        }
    }

    /**
     * @param array $compressDirs
     */
    protected function removeDirectoriesContent(array $compressDirs): void
    {
        foreach ($compressDirs as $index => $compressDir) {
            $compressDirectoryItterator = glob($compressDir . '/*');
            foreach ($compressDirectoryItterator as $file) {
                if (!strpos($file, '.gitempty')) {
                    unlink($file);
                }
            }
        }
    }
}
