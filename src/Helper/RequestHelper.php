<?php
declare(strict_types=1);

namespace ReviewParser\Helper;

use ReviewParser\Exception\ProblemWithDownloadPageException;
use ReviewParser\Strategy\IpRounderInterface;
use ReviewParser\Strategy\SmartStepByStepIpRounder;
use ReviewParser\Strategy\StepByStepIpRounder;

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
     * @var IpRounderInterface
     */
    private $ipRoundStrategy;

    /**
     * @var int
     */
    private $countAttempts;

    /**
     * RequestHelper constructor.
     *
     * @param array              $headers
     * @param int                $countAttempts
     * @param IpRounderInterface $ipRoundStrategy
     */
    public function __construct(array $headers, int $countAttempts = 1, IpRounderInterface $ipRoundStrategy = null)
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
                } else if ($error === ''){
                    // TODO move to special parser rules
                    $error = $this->ipHealthCheck($content);
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
        if ($this->ipRoundStrategy instanceof IpRounderInterface) {
            $iterator = $this->ipRoundStrategy->getIPIterator();
            if ($error !== '' && ($this->ipRoundStrategy instanceof StepByStepIpRounder | $this->ipRoundStrategy instanceof SmartStepByStepIpRounder)) {
                $this->ipRoundStrategy->nextElementByError($error);
                if ($iterator->count() > 0) {
                    [$error, $content] = $this->makeRequest($url);
                }
            } else {
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

        if ($this->ipRoundStrategy instanceof IpRounderInterface) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->ipRoundStrategy->getResponseTimeout());

            $iterator = $this->ipRoundStrategy->getIPIterator();
            if ($iterator->valid()) {
                $ip = $iterator->getIp();
                $port = $iterator->getPort();
                echo 'Url ' . $url;
                echo ' Current IP: ' . $ip;
                echo ' Current PORT: ' . $port;
                echo ' Current timout: ' . $this->ipRoundStrategy->getResponseTimeout() . PHP_EOL;
                curl_setopt($ch, CURLOPT_PROXY, $ip);
                curl_setopt($ch, CURLOPT_PROXYPORT, $port);
            }
        }
    }

    /**
     * @param string $pageContent
     * @return string
     */
    protected function ipHealthCheck(string $pageContent): string
    {
        $error = '';

        if (strpos($pageContent, 'name="captcha_url"') || strpos($pageContent, '<h1>Access Denied</h1>')) {
            $error = sprintf('IP %s is not valid now.',$this->ipRoundStrategy instanceof IpRounderInterface ? $this->ipRoundStrategy->getIPIterator()->getIp() : 'SELF');
        }

        return $error;
    }
}
