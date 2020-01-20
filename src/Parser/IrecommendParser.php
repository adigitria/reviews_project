<?php
declare(strict_types=1);

namespace ReviewParser\Parser;

use ReviewParser\ParserFactory;

class IrecommendParser extends AbstractReviewParser
{
    protected function pageParsing()
    {
        $baseSearchPage = $this->baseSearchUrl . '?page=';

        $opts = [
            'http' => [
                'method' => "GET",
                'header' => $this->headers['full_headers'],
            ],
        ];

        $context      = stream_context_create($opts);
        $linksPattern = '/<a href=\"([^"]+)\" class=\"more\"><\/a>/';

        for ($i = 1; $i <= $this->countPages; $i++) {
            $pageContent = file_get_contents($baseSearchPage . $i, false, $context);
            preg_match_all($linksPattern, $pageContent, $matches);
            foreach ($matches[1] as $index => $reviewPage) {
                $content = file_get_contents($this->getBaseSiteUrl() . $reviewPage, false, $context);
                file_put_contents($this->getPagesHtmlDir() . '/review_' . $i . '_' . $index . '.html', $content);
            }
            echo 'Download page - ' . $i . PHP_EOL;
        }
    }

    protected function gettingReviewInfoAsHtml()
    {
        $reviewPattern = '/<!-- review block start -->([\s\S]+)<!-- review block end -->/';
        $files         = scandir($this->getPagesHtmlDir());

        foreach ($files as $i => $file) {
            $filePath = $this->getPagesHtmlDir() . '/' . $file;
            if (file_exists($filePath) && !in_array($file, ['.', '..'])) {
                echo $filePath . PHP_EOL;
                $content = file_get_contents($filePath);
                preg_match($reviewPattern, $content, $matches);
                if (isset($matches[1])) {
                    file_put_contents($this->getReviewsHtmlDir() . '/review_' . ($i - 1) . '.html', $matches[1]);
                }
            }
        }
    }

    protected function gettingReviewsAsJson()
    {
        $files = scandir($this->getReviewsHtmlDir());

        $titlePattern   = '/<h2 class=\"reviewTitle\" [^>]+>\s*<a[^>]+>([\s\S]+?)<\/a>/';
        $contentPattern = '/<div class="description hasinlineimage" itemprop="reviewBody">([\s\S]+?)<\/div>/';
        $ratingPattern  = '/<meta itemprop=\"ratingValue\" content=\"([0-9]+)" \/>/';
        $datePattern    = '/<meta itemprop=\"datePublished\" content=\"(.+)\" \/>/';

        $patterns = [
            'title'   => $titlePattern,
            'content' => $contentPattern,
            'rating'  => $ratingPattern,
            'date'    => $datePattern,
        ];

        $result = [];
        foreach ($files as $i => $file) {
            $filePath = $this->getReviewsHtmlDir() . '/' . $file;
            if (file_exists($filePath) && !in_array($file, ['.', '..'])) {
                echo $filePath . PHP_EOL;
                $content = file_get_contents($filePath);

                foreach ($patterns as $key => $pattern) {
                    unset($matches);
                    preg_match($pattern, $content, $matches);
                    if (isset($matches[1])) {
                        $result[$i][$key] = trim($matches[1]);
                    }
                }
            }
        }

        file_put_contents($this->getResultsDir() . '/tele2_irecommend_reviews.json', json_encode($result, JSON_UNESCAPED_UNICODE));

    }

    protected function getBaseSiteUrl(): string
    {
        return 'https://irecommend.ru';
    }

    public function getParserAlias(): string
    {
        return ParserFactory::IRECOMMEND_ALIAS;
    }
}
