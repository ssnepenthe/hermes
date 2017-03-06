<?php

use SSNepenthe\Hermes\Loader\JsonFileLoader;
use Symfony\Component\Config\FileLocatorInterface;

class JsonFileLoaderTest extends ScraperTestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_can_determine_if_a_file_is_supported()
    {
        $locator = Mockery::mock(FileLocatorInterface::class);
        $loader = new JsonFileLoader($locator);

        $this->assertTrue(
            $loader->supports($this->getScraperFixturePath('one-deep.json'))
        );
        $this->assertFalse(
            $loader->supports($this->getScraperFixturePath('one-deep.yml'))
        );
    }

    /** @test */
    function it_can_load_and_decode_a_json_file()
    {
        $locator = Mockery::mock(FileLocatorInterface::class)
            ->shouldReceive('locate')
            ->once()
            ->andReturn($this->getScraperFixturePath('one-deep.json'))
            ->mock();
        $loader = new JsonFileLoader($locator);
        $configs = $loader->load($this->getScraperFixturePath('one-deep.json'));

        $this->assertEquals([
            [
                'matcher' => 'host:duckduckgo.com',
                'schema' => [
                    [
                        'extractor' => 'first:href',
                        'name' => 'icon',
                        'selector' => '[rel="apple-touch-icon"]',
                    ],
                ],
            ],
        ], $configs);
    }

    /** @test */
    function it_properly_handles_config_inheritance()
    {
        $locator = Mockery::mock(FileLocatorInterface::class)
            ->shouldReceive('locate')
            ->times(3)
            ->andReturn(
                $this->getScraperFixturePath('one-extension.json'),
                $this->getScraperFixturePath('one-deep.json'),
                $this->getScraperFixturePath('one-deep.json')
            )
            ->mock();
        $loader = new JsonFileLoader($locator);
        $configs = $loader->load($this->getScraperFixturePath('one-extension.json'));

        $this->assertEquals([
            [
                'matcher' => 'host:duckduckgo.com',
                'schema' => [
                    [
                        'extractor' => 'first:href',
                        'name' => 'icon',
                        'selector' => '[rel="apple-touch-icon"]',
                    ],
                ],
            ],
            [
                'matcher' => 'host:google.com',
            ],
        ], $configs);
    }

    /** @test */
    function it_properly_handles_multi_level_inheritance()
    {
        $locator = Mockery::mock(FileLocatorInterface::class)
            ->shouldReceive('locate')
            ->times(5)
            ->andReturn(
                $this->getScraperFixturePath('two-extensions.json'),
                $this->getScraperFixturePath('one-extension.json'),
                $this->getScraperFixturePath('one-extension.json'),
                $this->getScraperFixturePath('one-deep.json'),
                $this->getScraperFixturePath('one-deep.json')
            )
            ->mock();
        $loader = new JsonFileLoader($locator);
        $configs = $loader->load(
            $this->getScraperFixturePath('two-extensions.json')
        );

        $this->assertEquals([
            [
                'matcher' => 'host:duckduckgo.com',
                'schema' => [
                    [
                        'extractor' => 'first:href',
                        'name' => 'icon',
                        'selector' => '[rel="apple-touch-icon"]',
                    ],
                ],
            ],
            [
                'matcher' => 'host:google.com',
            ],
            [
                'schema' => [
                    [
                        'extractor' => 'all:href',
                        'name' => 'icon',
                    ],
                ],
            ],
        ], $configs);
    }
}
