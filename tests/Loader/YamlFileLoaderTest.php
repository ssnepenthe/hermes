<?php

use SSNepenthe\Hermes\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocatorInterface;

/**
 * Note: inheritance from base file loader is tested in json loader class.
 */
class YamlFileLoaderTest extends ScraperTestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_can_determine_if_a_file_is_supported()
    {
        $locator = Mockery::mock(FileLocatorInterface::class);
        $loader = new YamlFileLoader($locator);

        $this->assertTrue(
            $loader->supports($this->getScraperFixturePath('one-deep.yml'))
        );
        $this->assertTrue(
            $loader->supports($this->getScraperFixturePath('one-deep.yaml'))
        );
        $this->assertFalse(
            $loader->supports($this->getScraperFixturePath('one-deep.json'))
        );
    }

    /** @test */
    function it_can_load_a_yaml_file()
    {
        $locator = Mockery::mock(FileLocatorInterface::class)
            ->shouldReceive('locate')
            ->once()
            ->andReturn($this->getScraperFixturePath('one-deep.yml'))
            ->mock();
        $loader = new YamlFileLoader($locator);
        $configs = $loader->load($this->getScraperFixturePath('one-deep.yml'));

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
}
