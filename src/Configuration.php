<?php
declare(strict_types=1);

namespace ReviewParser;

use ReviewParser\Exception\InvalidArgumentException;
use ReviewParser\Model\IpBlockConfiguration;

/**
 * Class Configuration
 * @package ReviewParser
 */
class Configuration
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $baseSearchUrl;

    /**
     * @var int
     */
    private $countPages;

    /**
     * @var IpBlockConfiguration
     */
    private $ipConfiguration;

    /**
     * Configuration constructor.
     *
     * @param array $argv
     * @param array $config
     */
    public function __construct(array $argv, array $config)
    {
        $this->config          = $config;
        $this->ipConfiguration = new IpBlockConfiguration($config['ip']);
        $this->setUrlAndParserAlias($argv);
        $this->setCountPages($argv);
    }

    /**
     * @param string $alias
     *
     * @return array
     */
    public function getRequestHeaders(string $alias): array
    {
        $initialHeaders          = $this->config['headers'][$alias];
        $additionalHeaderPattern = '%s: %s';

        $mainKeys = [
            'Cookie'          => CURLOPT_COOKIE,
            'Accept-Encoding' => CURLOPT_ACCEPT_ENCODING,
            'Referer'         => CURLOPT_REFERER,
            'User-Agent'      => CURLOPT_USERAGENT,
        ];

        $notEmptyHeaders = array_filter(
            $initialHeaders, function (string $value) {
            return trim($value) !== '';
        }
        );

        $requestHeaders = [];
        foreach ($notEmptyHeaders as $key => $value) {
            if (isset($mainKeys[$key])) {
                $requestHeaders[$mainKeys[$key]] = $value;
            } else {
                $requestHeaders[CURLOPT_HTTPHEADER][] = sprintf($additionalHeaderPattern, $key, $value);
            }
        }

        return $requestHeaders;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getBaseSearchUrl(): string
    {
        return $this->baseSearchUrl;
    }

    /**
     * @return int
     */
    public function getCountPages(): int
    {
        return $this->countPages;
    }

    /**
     * @return IpBlockConfiguration
     */
    public function getIpConfiguration(): IpBlockConfiguration
    {
        return $this->ipConfiguration;
    }

    /**
     * @return int
     */
    public function getCountAttempts(): int
    {
        return $this->config['count_attempts'];
    }

    /**
     * @param array $argv
     */
    protected function setUrlAndParserAlias(array $argv): void
    {
        if (isset($argv[1])) {
            preg_match('/https:\/\/[w\.]*([^\/]+)\.[rucom]{2,3}/', $argv[1], $matches);
            if (isset($matches[1])) {
                $this->alias         = $matches[1];
                $this->baseSearchUrl = $argv[1];
            } else {
                throw new InvalidArgumentException('BaseSearchUrl is not correct.');
            }
        } else {
            throw new InvalidArgumentException('BaseSearchUrl argument is empty.');
        }

    }

    /**
     * @param array $argv
     */
    protected function setCountPages(array $argv): void
    {
        if (isset($argv[2])) {
            $this->countPages = (int) $argv[2];
        } else {
            throw new InvalidArgumentException('CountPages argument is empty.');
        }
    }
}
