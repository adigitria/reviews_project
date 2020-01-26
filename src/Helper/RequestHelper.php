<?php
declare(strict_types=1);

namespace ReviewParser\Helper;

use ReviewParser\Exception\ProblemWithDownloadPageException;
use ReviewParser\Model\IPIterator;

/**
 * Class RequestHelper
 * @package ReviewParser\Helper
 */
class RequestHelper
{
    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var IPIterator
     */
    private $IPIterator;

    /**
     * @var int
     */
    private $countAttempts;

    /**
     * RequestHelper constructor.
     *
     * @param array           $headers
     * @param int             $countAttempts
     * @param IPIterator|null $IPIterator
     */
    public function __construct(array $headers, int $countAttempts = 1, IPIterator $IPIterator = null)
    {
        $this->headers       = $headers;
        $this->IPIterator    = $IPIterator;
        $this->countAttempts = $countAttempts;
    }

    /**
     * @param string $url
     *
     * @return array
     */
    public function makeRequest(string $url): array
    {
        $ch = curl_init();

        try {
            $this->makeCurlSettings($url, $ch);

            for ($i = 0; $i < $this->countAttempts; $i++) {
                $error   = '';
                $content = curl_exec($ch);

                if (curl_errno($ch) !== 0) {
                    $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    if ($responseCode !== 200) {
                        $error = 'Got no 200 code. Response code: ' . $responseCode;
                    } else {
                        $error = 'Curl Error: ' . curl_error($ch);
                    }
                } else if ($content === false) {
                    $error = 'Could not get an answer from ' . $url;
                }
                if ($error === '') {
                    break;
                }
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
     * @param        $ch
     */
    protected function makeCurlSettings(string $url, $ch): void
    {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        if (!empty($this->headers)) {
            curl_setopt_array($ch, $this->headers);
        }

        if ($this->IPIterator instanceof IPIterator) {
            if ($this->IPIterator->valid()) {
                curl_setopt($ch, CURLOPT_PROXY, $this->IPIterator->getIp());
                curl_setopt($ch, CURLOPT_PROXYPORT, $this->IPIterator->getPort());
                $this->IPIterator->next();
            }
        }
    }
}
