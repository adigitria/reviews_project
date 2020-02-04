<?php
declare(strict_types=1);


namespace ReviewParser\Model;


/**
 * Class IpBlockConfiguration
 * @package ReviewParser\Model
 */
class IpBlockConfiguration
{
    /**
     * @var bool
     */
    private $enable;

    /**
     * @var array
     */
    private $list = [];

    /**
     * @var bool
     */
    private $isAutoReDownload;

    /**
     * @var int
     */
    private $connectionTimeout;


    /**
     * IpBlockConfiguration constructor.
     *
     * @param array $IPConfig
     */
    public function __construct(array $IPConfig)
    {
        $this->enable            = $IPConfig['enable'];
        $this->list              = $IPConfig['list'];
        $this->isAutoReDownload  = $IPConfig['auto_re_download'];
        $this->connectionTimeout = $IPConfig['connection_timeout'];
    }

    /**
     * @return bool
     */
    public function isIpBlockEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        return $this->list;
    }

    /**
     * @return bool
     */
    public function isAutoReDownload(): bool
    {
        return $this->isAutoReDownload;
    }

    /**
     * @return int
     */
    public function getConnectionTimeout(): int
    {
        return $this->connectionTimeout;
    }
}
