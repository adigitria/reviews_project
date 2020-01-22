<?php
declare(strict_types=1);

namespace ReviewParser;

use ReviewParser\Exception\InvalidArgumentException;

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
     * Configuration constructor.
     *
     * @param array $argv
     * @param array $config
     */
    public function __construct(array $argv, array $config)
    {
        $this->config = $config;
        $this->setUrlAndParserAlias($argv);
        $this->setCountPages($argv);
    }

    /**
     * @param string $alias
     *
     * @return array
     */
    public function getHeaders(string $alias): array
    {
        return $this->config['headers'][$alias];
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
