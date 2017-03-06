<?php

use SSNepenthe\Hermes\Converter\ConverterStack;
use SSNepenthe\Hermes\Matcher\MatcherInterface;
use SSNepenthe\Hermes\Normalizer\NormalizerStack;
use Symfony\Component\Config\Definition\Processor;
use SSNepenthe\Hermes\Converter\ConverterInterface;
use SSNepenthe\Hermes\Extractor\ExtractorInterface;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;
use SSNepenthe\Hermes\Definition\ScraperConfiguration;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ScraperConfigurationTest extends PHPUnit\Framework\TestCase
{
    /** @test */
    function it_properly_applies_max_schema_depth()
    {
        $configuration = new ScraperConfiguration(2);
        $configs = $this->getConfigs('three-deep.php');
        $processor = new Processor;

        try {
            $processed = $processor->processConfiguration($configuration, $configs);
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidConfigurationException::class, $e);
            $this->assertEquals(
                'Unrecognized option "schema" under "scraper.schema.results.schema.result"',
                $e->getMessage()
            );
        }
    }

    /** @test */
    function it_properly_applies_custom_matcher_validation()
    {
        $configuration = new ScraperConfiguration;
        $configs = $this->getConfigs('bad-matcher.php');
        $processor = new Processor;

        try {
            $processed = $processor->processConfiguration($configuration, $configs);
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidConfigurationException::class, $e);
            $this->assertEquals(
                'Invalid configuration for path "scraper.matcher": Invalid matcher "bad-matcher"',
                $e->getMessage()
            );
        }
    }

    /** @test */
    function it_properly_applies_custom_converter_validation()
    {
        $configuration = new ScraperConfiguration;
        $configs = $this->getConfigs('bad-converter.php');
        $processor = new Processor;

        try {
            $processed = $processor->processConfiguration($configuration, $configs);
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidConfigurationException::class, $e);
            $this->assertEquals(
                'Invalid configuration for path "scraper.schema.icon.converters": Invalid converter "bad-converter"',
                $e->getMessage()
            );
        }
    }

    /** @test */
    function it_properly_applies_custom_extractor_validation()
    {
        $configuration = new ScraperConfiguration;
        $configs = $this->getConfigs('bad-extractor.php');
        $processor = new Processor;

        try {
            $processed = $processor->processConfiguration($configuration, $configs);
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidConfigurationException::class, $e);
            $this->assertEquals(
                'Invalid configuration for path "scraper.schema.icon.extractor": Invalid extractor "bad-extractor:href"',
                $e->getMessage()
            );
        }
    }

    /** @test */
    function it_properly_applies_custom_normalizer_validation()
    {
        $configuration = new ScraperConfiguration;
        $configs = $this->getConfigs('bad-normalizer.php');
        $processor = new Processor;

        try {
            $processed = $processor->processConfiguration($configuration, $configs);
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidConfigurationException::class, $e);
            $this->assertEquals(
                'Invalid configuration for path "scraper.schema.icon.normalizers": Invalid normalizer "bad-normalizer"',
                $e->getMessage()
            );
        }
    }

    /** @test */
    function it_properly_resolves_closure_values()
    {
        $configuration = new ScraperConfiguration;
        $configs = $this->getConfigs('closure-values.php');
        $processor = new Processor;

        $processed = $processor->processConfiguration($configuration, $configs);

        $this->assertInstanceOf(
            ConverterInterface::class,
            $processed['schema']['icon']['converters']
        );
        $this->assertInstanceOf(
            ExtractorInterface::class,
            $processed['schema']['icon']['extractor']
        );
        $this->assertInstanceOf(
            MatcherInterface::class,
            $processed['schema']['icon']['matcher']
        );
        $this->assertInstanceOf(
            NormalizerInterface::class,
            $processed['schema']['icon']['normalizers']
        );
    }

    /** @test */
    function it_properly_resolves_alias_strings()
    {
        $configuration = new ScraperConfiguration;
        $configs = $this->getConfigs('alias-values.php');
        $processor = new Processor;

        $processed = $processor->processConfiguration($configuration, $configs);

        $this->assertInstanceOf(
            ConverterInterface::class,
            $processed['schema']['icon']['converters']
        );
        $this->assertInstanceOf(
            ExtractorInterface::class,
            $processed['schema']['icon']['extractor']
        );
        $this->assertInstanceOf(
            MatcherInterface::class,
            $processed['schema']['icon']['matcher']
        );
        $this->assertInstanceOf(
            NormalizerInterface::class,
            $processed['schema']['icon']['normalizers']
        );
    }

    /** @test */
    function it_properly_resolves_fqcn_strings()
    {
        $configuration = new ScraperConfiguration;
        $configs = $this->getConfigs('fqcn-values.php');
        $processor = new Processor;

        $processed = $processor->processConfiguration($configuration, $configs);

        $this->assertInstanceOf(
            ConverterInterface::class,
            $processed['schema']['icon']['converters']
        );
        $this->assertInstanceOf(
            ExtractorInterface::class,
            $processed['schema']['icon']['extractor']
        );
        $this->assertInstanceOf(
            MatcherInterface::class,
            $processed['schema']['icon']['matcher']
        );
        $this->assertInstanceOf(
            NormalizerInterface::class,
            $processed['schema']['icon']['normalizers']
        );
    }

    /** @test */
    function it_creates_stacks_where_appropriate()
    {
        $configuration = new ScraperConfiguration;
        $configs = $this->getConfigs('stacked-values.php');
        $processor = new Processor;

        $processed = $processor->processConfiguration($configuration, $configs);

        $this->assertInstanceOf(
            ConverterStack::class,
            $processed['schema']['icon']['converters']
        );
        $this->assertInstanceOf(
            NormalizerStack::class,
            $processed['schema']['icon']['normalizers']
        );
    }

    // ======================================================================= //
    // =============================== HELPERS =============================== //
    // ======================================================================= //

    protected function getConfigs(string $file) : array
    {
        $dir = realpath(dirname(__DIR__) . '/fixtures/scrapers');
        $file = $dir . DIRECTORY_SEPARATOR . $file;
        $config = include $file;

        return [$config];
    }
}
