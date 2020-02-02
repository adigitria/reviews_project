<?php
declare(strict_types=1);

namespace ReviewParser\Helper;

use ReviewParser\Exception\ProblemWithDownloadPageException;
use ReviewParser\Strategy\IpRoundInterface;
use ReviewParser\Strategy\StepByStepIpRound;

/**
 * Class RequestHelper
 * @package ReviewParser\Helper
 */
class RequestHelper
{
    /**
     * @var array
     */
    private $headers;

    /**
     * @var IpRoundInterface
     */
    private $ipRoundStrategy;

    /**
     * @var int
     */
    private $countAttempts;

    /**
     * RequestHelper constructor.
     *
     * @param array            $headers
     * @param int              $countAttempts
     * @param IpRoundInterface $ipRoundStrategy
     */
    public function __construct(array $headers, int $countAttempts = 1, IpRoundInterface $ipRoundStrategy = null)
    {
        $this->headers         = $headers;
        $this->ipRoundStrategy = $ipRoundStrategy;
        $this->countAttempts   = $countAttempts;
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
            $error = '';

            for ($i = 0; $i < $this->countAttempts; $i++) {
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

        /*
         * If we got error in response and use StepByStep IpRound strategy
         * then remove bad IP from IP-collection and if this collection is not empty
         * try to download content with the next IP.
         * */
        if($this->ipRoundStrategy instanceof IpRoundInterface){
            $iterator = $this->ipRoundStrategy->getIPIterator();
            if ($error !== '' && $this->ipRoundStrategy instanceof StepByStepIpRound) {
                $iterator->removeCurrent();
                if ($iterator->count() > 0) {
                    list($error, $content) = $this->makeRequest($url);
                }
            }else{
                $iterator->next();
            }
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

        if ($this->ipRoundStrategy instanceof IpRoundInterface) {
            $iterator = $this->ipRoundStrategy->getIPIterator();
            if ($iterator->valid()) {
                echo 'Current IP: '.$iterator->getIp().PHP_EOL;
                curl_setopt($ch, CURLOPT_PROXY, $iterator->getIp());
                curl_setopt($ch, CURLOPT_PROXYPORT, $iterator->getPort());
            }
        }
    }
}
