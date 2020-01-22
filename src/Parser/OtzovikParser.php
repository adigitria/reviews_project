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
        $agent = $this->headers['user_agent'];

        $ch = curl_init();


        $count = 0;
        for ($i = 1; $i <= $this->countPages; $i++) {
            $searchUrl = str_replace('%PAGE_NUMBER%', $i, $url);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
            curl_setopt($ch, CURLOPT_COOKIE, $this->headers['cookie']);
            curl_setopt($ch, CURLOPT_URL, $searchUrl);

            $result = curl_exec($ch);
            file_put_contents($this->getPagesHtmlDir() . '/page_' . $i . '.html', $result);
            $count++;
            if ($count % 20 == 0) {
                sleep(15);
            }
        }

        curl_close($ch);
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
