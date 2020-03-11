<?php
declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use ReviewParser\Configuration;

class ConfigurationTest extends TestCase
{
    /**
     * @dataProvider dataGetBaseSearchUrl
     *
     * @param array  $inputArguments
     * @param array  $config
     * @param string $expectedUrl
     */
    public function testGetBaseSearchUrl(array $inputArguments, array $config, string $expectedUrl)
    {
        $configuration = new Configuration($inputArguments, $config);
        $this->assertEquals($expectedUrl, $configuration->getBaseSearchUrl());
    }

    public function dataGetBaseSearchUrl()
    {
        [$inputArguments, $config] = $this->getBaseConfigurationData();
        $expectedUrl = 'https://banki.ru/services/responses/bank/test/';

        return [
            [
                $inputArguments,
                $config,
                $expectedUrl,
            ],
        ];
    }

    /**
     * @dataProvider dataGetAlias
     *
     * @param array  $inputArguments
     * @param array  $config
     * @param string $expectedAlias
     */
    public function testGetAlias(array $inputArguments, array $config, string $expectedAlias)
    {
        $configuration = new Configuration($inputArguments, $config);
        $this->assertEquals($expectedAlias, $configuration->getAlias());
    }

    public function dataGetAlias()
    {
        [$inputArguments, $config] = $this->getBaseConfigurationData();
        $expectedAlias = 'banki';

        return [
            [
                $inputArguments,
                $config,
                $expectedAlias,
            ],
        ];
    }

    public function testGetCountAttempts()
    {

    }


    public function testGetFinalPageNumber()
    {

    }

    public function testGetStartPageNumber()
    {

    }

    public function testSetStartPageNumber()
    {

    }

    public function testGetRequestHeaders()
    {

    }

    public function testGetIpConfiguration()
    {

    }

    /**
     * @return array
     */
    protected function getBaseConfigurationData(): array
    {
        $inputArguments = [
            './runner',
            'https://banki.ru/services/responses/bank/test/',
            100,
        ];

        $config = [
            'ip' => [
                'enable'             => true,
                'ip_strategy_type'   => 'default',
                'list'               => [],
                'connection_timeout' => 0,
            ]];

        return [$inputArguments, $config];
    }
}
