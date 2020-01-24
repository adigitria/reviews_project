<?php
declare(strict_types=1);

namespace ReviewParser\Helper;

use ReviewParser\Exception\ProblemWithDownloadPageException;

class RequestHelper
{
    private $headers = [];

    /**
     * RequestHelper constructor.
     * @param array $headers
     */
    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param string $url
     * @return array
     */
    public function makeRequest(string $url): array
    {
        $ch = curl_init();

        try {
            $error = '';

            $this->makeCurlSettings($url, $ch);

            $content = curl_exec($ch);

            if (curl_errno($ch) !== 0) {
                $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if($responseCode !== 200){
                    $error = 'Got no 200 code. Response code: ' . $responseCode;
                }else{
                    $error = 'Curl Error: '. curl_error($ch);
                }
            } elseif ($content === false) {
                $error = 'Could not get an answer from ' . $url;
            }
        } catch (\Throwable $exception) {
            throw new ProblemWithDownloadPageException('Unexpected Error: ' . $exception->getMessage(), $url);
        } finally {
            curl_close($ch);
        }

        return [$error, $content];
    }

    /**
     * @param string $url
     * @param $ch
     */
    protected function makeCurlSettings(string $url, $ch): void
    {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        if (!empty($this->headers)) {
            curl_setopt_array($ch, $this->headers);
        }

//        TODO add ability to using list of proxy IP
//        curl_setopt($ch, CURLOPT_PROXY, '');
//        curl_setopt($ch, CURLOPT_PROXYPORT, '');
    }
}