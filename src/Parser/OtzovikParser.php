<?php
declare(strict_types=1);

namespace ReviewParser\Parser;

use ReviewParser\ParserFactory;

class OtzovikParser extends AbstractReviewParser
{
    protected function pagesParsing()
    {
        for ($i = $this->startPageNumber; $i <= $this->finalPageNumber; $i++) {

            $url = str_replace('%PAGE_NUMBER%', $i, $this->baseSearchUrl . '%PAGE_NUMBER%/');
            $linksPattern = '/\<a class=\"review-title\" href=\"(.+)\" itemprop=\"name\"\>/';
            $pageContent = $this->safeGetContentByCurl($url);
            file_put_contents($this->getPagesHtmlDir() . '/base_review_' . $i . '.html', $pageContent);

            preg_match_all($linksPattern, $pageContent, $matches);

            foreach ($matches[1] as $index => $reviewPage) {
                $url = $this->getBaseSiteUrl() . $reviewPage;
                $content = $this->safeGetContentByCurl($url);
                file_put_contents($this->getPagesHtmlDir() . '/review_' . $i . '_' . $index . '.html', $content);
            }
        }
    }

    protected function gettingReviewInfoAsHtml()
    {
        $reviewPattern = '/(<h1>[\s\S]+?)<div class="review-panel">/';
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

        $titlePattern   = '/<h1>([\s\S]+)<\/h1>/';
        $contentPattern = '/\<div class=\"review-body description\" itemprop=\"description\"\>([\s\S]+?)<\/div\>/';
        $ratingPattern  = '/\"Общий рейтинг\: ([0-9]{1})\"/';
        $datePattern    = '/\<span class=\"review-postdate dtreviewed\"\>[\s\S]+?title="([0-9-]+)\"\>[\s\S]+?<\/span\>/';

        $patterns = [
            'title'   => $titlePattern,
            'content' => $contentPattern,
            'rating'  => $ratingPattern,
            'date'    => $datePattern,
        ];

        $result = [];
        foreach ($files as $i => $file) {
            $filePath = $this->getReviewsHtmlDir() . '/' . $file;
            if (file_exists($filePath) && !in_array($file, ['.', '..', '.gitempty'])) {
                echo $filePath . PHP_EOL;
                $content = file_get_contents($filePath);

                foreach ($patterns as $key => $pattern) {
                    unset($matches);
                    preg_match($pattern, $content, $matches);
                    if (isset($matches[1])) {
                        $value = trim($matches[1]);
                        if ($key === 'date') {
                            $value = $value.' 12:00:00';
                        }
                        $result[$i][$key] = $value;
                    } else {
                        $result[$i][$key] = '';
                    }
                }
            }
        }

        file_put_contents($this->getResultsDir() . '/'.$this->getResultFileName(), json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    protected function getBaseSiteUrl(): string
    {
        return 'https://otzovik.com';
    }


    public function getParserAlias(): string
    {
        return ParserFactory::OTZOVIK_ALIAS;
    }
}
