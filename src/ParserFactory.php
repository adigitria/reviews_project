<?php
declare(strict_types=1);

namespace ReviewParser;

use ReviewParser\Exception\IpRounderStrategyNotFoundException;
use ReviewParser\Exception\ParserNotFoundException;
use ReviewParser\Helper\Logger;
use ReviewParser\Helper\RequestHelper;
use ReviewParser\Model\IpBlockConfiguration;
use ReviewParser\Model\IPIterator;
use ReviewParser\Parser\BankiParser;
use ReviewParser\Parser\IrecommendParser;
use ReviewParser\Parser\OtzovikParser;
use ReviewParser\Parser\ReviewParserInterface;
use ReviewParser\Strategy\DefaultIpRounder;
use ReviewParser\Strategy\IpRounderInterface;
use ReviewParser\Strategy\SmartStepByStepIpRounder;
use ReviewParser\Strategy\StepByStepIpRounder;

class ParserFactory
{
    public const BANKIRU_ALIAS    = 'banki';
    public const IRECOMMEND_ALIAS = 'irecommend';
    public const OTZOVIK_ALIAS    = 'otzovik';

    public static function makeParser(Configuration $configuration): ReviewParserInterface
    {
        $requestHelper = new RequestHelper(
            $configuration->getRequestHeaders($configuration->getAlias()),
            $configuration->getCountAttempts(),
            self::makeIpStrategy($configuration)
        );

        if ($configuration->getAlias() === self::BANKIRU_ALIAS) {
            $parser = new BankiParser(
                $requestHelper,
                $configuration->getBaseSearchUrl(),
                $configuration->getStartPageNumber(),
                $configuration->getFinalPageNumber()
            );
        } else if ($configuration->getAlias() === self::IRECOMMEND_ALIAS) {
            $parser = new IrecommendParser(
                $requestHelper,
                $configuration->getBaseSearchUrl(),
                $configuration->getStartPageNumber(),
                $configuration->getFinalPageNumber()
            );
        } else if ($configuration->getAlias() === self::OTZOVIK_ALIAS) {
            $parser = new OtzovikParser(
                $requestHelper,
                $configuration->getBaseSearchUrl(),
                $configuration->getStartPageNumber(),
                $configuration->getFinalPageNumber()
            );
        } else {
            throw new ParserNotFoundException(ParserNotFoundException::ERROR_MESSAGE);
        }

        $parser->setConnectionLog(new Logger('logs/connection.log'));

        return $parser;
    }

    private static function makeIpStrategy(Configuration $configuration): ?IpRounderInterface
    {
        $strategy      = null;
        $ipBlockConfig = $configuration->getIpConfiguration();

        if ($ipBlockConfig->isIpBlockEnable()) {
            $ipIterator = new IPIterator($ipBlockConfig->getList());
            switch ($ipBlockConfig->getStrategyType()) {
                case IpBlockConfiguration::DEFAULT_STRATEGY_TYPE:
                    $strategy = new DefaultIpRounder($ipIterator, $ipBlockConfig);
                    break;
                case IpBlockConfiguration::STEP_BY_STEP_STRATEGY_TYPE:
                    $strategy = new StepByStepIpRounder($ipIterator, $ipBlockConfig);
                    break;
                case IpBlockConfiguration::SMART_STEP_BY_STEP_STRATEGY_TYPE:
                    $strategy = new SmartStepByStepIpRounder($ipIterator, $ipBlockConfig);
                    break;
                default:
                    throw new IpRounderStrategyNotFoundException(IpRounderStrategyNotFoundException::ERROR_MESSAGE);
            }
        }

        return $strategy;
    }
}
