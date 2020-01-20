<?php
declare(strict_types=1);

namespace ReviewParser;

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
     */
    public function __construct(array $argv, array $config)
    {
        $this->config        = $config;
        $this->alias         = $argv[1];
        $this->baseSearchUrl = $argv[2];
        $this->countPages    = (int) $argv[3];
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
}
