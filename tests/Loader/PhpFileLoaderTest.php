<?php

use SSNepenthe\Hermes\Loader\PhpFileLoader;
use Symfony\Component\Config\FileLocatorInterface;

/**
 * Note: inheritance from base file loader is tested in json loader class.
 */
class PhpFileLoaderTest extends ScraperTestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_can_determine_if_a_file_is_supported()
    {
        $locator = Mockery::mock(FileLocatorInterface::class);
        $loader = new PhpFileLoader($locator);

        $this->assertTrue(
            $loader->supports($this->getScraperFixturePath('one-deep.php'))
        );
        $this->assertFalse(
            $loader->supports($this->getScraperFixturePath('one-deep.yml'))
        );
    }

    /** @test */
    function it_can_load_a_php_file()
    {
        $locator = Mockery::mock(FileLocatorInterface::class)
            ->shouldReceive('locate')
            ->once()
            ->andReturn($this->getScraperFixturePath('one-deep.php'))
            ->mock();
        $loader = new PhpFileLoader($locator);
        $configs = $loader->load($this->getScraperFixturePath('one-deep.php'));

        $this->assertEquals([
            [
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
