<?php

use SSNepenthe\Hermes\Converter\ToInterval;
use SSNepenthe\Hermes\Converter\NullConverter;
use SSNepenthe\Hermes\Normalizer\NullNormalizer;
use Symfony\Component\Config\Definition\Processor;
use SSNepenthe\Hermes\Definition\ScraperConfiguration;
use SSNepenthe\Hermes\Normalizer\ConsecutiveHorizontalSpace;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ScraperConfigurationTest extends PHPUnit\Framework\TestCase
{
    /** @test */
    function it_properly_applies_default_converters_given_none_in_config()
    {
        $configuration = new ScraperConfiguration;
        $configuration->setDefaultConverters([NullConverter::class]);

        $configs = $this->getConfigs('https---duckduckgo-com-html-q-chrome.php');

        $processor = new Processor;
        $processed = $processor->processConfiguration($configuration, $configs);

        $this->assertEquals(
            [new NullConverter],
            $processed['schema']['icon']['converters']
        );
    }

    /** @test */
    function it_properly_applies_default_converters_given_preexisting_in_config()
    {
        $configuration = new ScraperConfiguration;
        $configuration->setDefaultConverters([NullConverter::class]);

        $configs = $this->getConfigs('https---duckduckgo-com-html-q-chrome.php');
        $configs[0]['schema'][0]['converters'] = [ToInterval::class];

        $processor = new Processor;
        $processed = $processor->processConfiguration($configuration, $configs);

        $this->assertEquals(
            [new NullConverter, new ToInterval],
            $processed['schema']['icon']['converters']
        );
    }

    /** @test */
    function it_properly_applies_default_normalizers_given_none_in_config()
    {
        $configuration = new ScraperConfiguration;
        $configuration->setDefaultNormalizers([NullNormalizer::class]);

        $configs = $this->getConfigs('https---duckduckgo-com-html-q-chrome.php');

        $processor = new Processor;
        $processed = $processor->processConfiguration($configuration, $configs);

        $this->assertEquals(
            [new NullNormalizer],
            $processed['schema']['icon']['normalizers']
        );
    }

    /** @test */
    function it_properly_applies_default_normalizers_given_preexisting_in_config()
    {
        $configuration = new ScraperConfiguration;
        $configuration->setDefaultNormalizers([NullNormalizer::class]);

        $configs = $this->getConfigs('https---duckduckgo-com-html-q-chrome.php');
        $configs[0]['schema'][0]['normalizers'] = [ConsecutiveHorizontalSpace::class];

        $processor = new Processor;
        $processed = $processor->processConfiguration($configuration, $configs);

        $this->assertEquals(
            [new NullNormalizer, new ConsecutiveHorizontalSpace],
            $processed['schema']['icon']['normalizers']
        );
    }

    /** @test */
    function it_properly_applies_max_schema_depth()
    {
        $this->expectException(InvalidConfigurationException::class);

        $configuration = new ScraperConfiguration;
        $configuration->setMaxDepth(1);

        $configs = $this->getConfigs('https---duckduckgo-com-html-q-safari.php');

        $processor = new Processor;
        $processed = $processor->processConfiguration($configuration, $configs);
    }

    // ======================================================================= //
    // =============================== HELPERS =============================== //
    // ======================================================================= //

    protected function getConfigs(string $file) : array
    {
        $dir = realpath(dirname(__DIR__) . '/_data/scrapers');
        $file = $dir . DIRECTORY_SEPARATOR . $file;
        $config = include $file;

        return [$config];
    }
}
