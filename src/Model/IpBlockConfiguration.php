<?php
declare(strict_types=1);


namespace ReviewParser\Model;


/**
 * Class IpBlockConfiguration
 * @package ReviewParser\Model
 */
class IpBlockConfiguration
{
    public const DEFAULT_STRATEGY_TYPE            = 'default';
    public const STEP_BY_STEP_STRATEGY_TYPE       = 'step_by_step';
    public const SMART_STEP_BY_STEP_STRATEGY_TYPE = 'smart_step_by_step';

    /**
     * @var bool
     */
    private $iBlockEnable;

    /**
     * @var array
     */
    private $list;

    /**
     * @var string
     */
    private $strategyType;

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
        $this->iBlockEnable      = $IPConfig['enable'];
        $this->list              = $IPConfig['list'];
        $this->strategyType      = $IPConfig['ip_strategy_type'];
        $this->connectionTimeout = $IPConfig['connection_timeout'];
    }

    /**
     * @return bool
     */
    public function isIpBlockEnable(): bool
    {
        return $this->iBlockEnable;
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        return $this->list;
    }

    /**
     * @return string
     */
    public function getStrategyType(): string
    {
        return $this->strategyType;
    }

    /**
     * @return int
     */
    public function getConnectionTimeout(): int
    {
        return $this->connectionTimeout;
    }
}
