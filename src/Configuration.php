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
        var_dump($argv);
        $this->setParserAlias($argv);
        $this->setBaseSearchUrl($argv);
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
    protected function setParserAlias(array $argv): void
    {
        if (isset($argv[1])) {
            $alias = trim($argv[1]);
            if (in_array($alias, ParserFactory::VALID_ALIASES, true)) {
                $this->alias = $alias;
            } else {
                throw new InvalidArgumentException('Alias argument is unavailable.');
            }
        } else {
            throw new InvalidArgumentException('Alias argument is empty.');
        }
    }

    /**
     * @param array $argv
     */
    protected function setBaseSearchUrl(array $argv): void
    {
        if (isset($argv[2])) {
            $this->baseSearchUrl = $argv[2];
        } else {
            throw new InvalidArgumentException('BaseSearchUrl argument is empty.');
        }
    }

    /**
     * @param array $argv
     */
    protected function setCountPages(array $argv): void
    {
        if (isset($argv[3])) {
            $this->countPages = (int) $argv[3];
        } else {
            throw new InvalidArgumentException('CountPages argument is empty.');
        }
    }
}
