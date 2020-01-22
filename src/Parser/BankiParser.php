<?php
declare(strict_types=1);

namespace ReviewParser\Parser;

use ReviewParser\ParserFactory;

class BankiParser extends AbstractReviewParser
{
    protected function pageParsing()
    {
        $baseSearchPage = $this->baseSearchUrl . '?page=';

        for ($i = 1; $i <= $this->countPages; $i++) {
            $pageContent = file_get_contents($baseSearchPage . $i);
            file_put_contents($this->getPagesHtmlDir() . '/page_' . $i . '.html', $pageContent);
            echo 'Download page - ' . $i . PHP_EOL;
        }
    }

    protected function gettingReviewInfoAsHtml()
    {
        $reviewPattern = '/<article([\s|\S]+?)<\/article>/';
        $files         = scandir($this->getPagesHtmlDir());

        foreach ($files as $i => $file) {
            $filePath = $this->getPagesHtmlDir() . '/' . $file;
            if (file_exists($filePath) && !in_array($file, ['.', '..'])) {
                echo $filePath . PHP_EOL;
                $content = file_get_contents($filePath);
                preg_match_all($reviewPattern, $content, $matches);
                foreach ($matches[0] as $index => $match) {
                    file_put_contents('reviews/banki/review_' . ($i - 1) . '_' . $index . '.html', $match);
                }
            }
        }
    }

    protected function gettingReviewsAsJson()
    {
        $files = scandir($this->getReviewsHtmlDir());

        $titlePattern   = '/<a class=\"header-h3\"[^<]+>(.+)<\/a>/';
        $contentPattern = '/<div class=\"responses__item__message markup-inside-small markup-inside-small--bullet\"[^>]+>([\S|\s]+?)<\/div>/';
        $ratingPattern  = '/itemprop=\"ratingValue\"[^>]+>([\S|\s]+?)<\/span>/';
        $datePattern    = '/>([0-9-:. ]+)<\/time>/';

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
                    } else {
                        $result[$i][$key] = '';
                    }
                }
            }
        }

        file_put_contents($this->getResultsDir() . '/banki_reviews.json', json_encode($result, JSON_UNESCAPED_UNICODE));
    }

    public function getParserAlias(): string
    {
        return ParserFactory::BANKIRU_ALIAS;
    }

    protected function getBaseSiteUrl(): string
    {
        return 'https://www.banki.ru/';
    }
}
