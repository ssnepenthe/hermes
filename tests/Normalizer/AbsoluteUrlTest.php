<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Normalizer\AbsoluteUrl;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;

class AbsoluteUrlTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable_and_callable()
    {
        $n = new AbsoluteUrl;

        $this->assertInstanceOf(NormalizerInterface::class, $n);
        $this->assertTrue(is_callable($n));
    }

    /** @test */
    function it_bails_if_given_non_url()
    {
        $n = new AbsoluteUrl;
        $c = Mockery::mock(Crawler::class)
            ->shouldReceive('getUri')
            ->andReturn('https://example.com')
            ->once()
            ->mock();

        $this->assertEquals(
            ['just a regular string'],
            $n('just a regular string', $c)
        );
    }

    /** @test */
    function it_fixes_schemeless_urls()
    {
        $n = new AbsoluteUrl;
        $c = Mockery::mock(Crawler::class)
            ->shouldReceive('getUri')
            ->andReturn('https://example.com')
            ->once()
            ->mock();

        $this->assertEquals(
            ['https://example.com/path/to/image.jpg'],
            $n('//example.com/path/to/image.jpg', $c)
        );
    }

    /** @test */
    function it_fixes_hostless_urls()
    {
        $n = new AbsoluteUrl;
        $c = Mockery::mock(Crawler::class)
            ->shouldReceive('getUri')
            ->andReturn('https://example.com')
            ->once()
            ->mock();

        $this->assertEquals(
            ['https://example.com/path/to/image.jpg'],
            $n('/path/to/image.jpg', $c)
        );
    }
}
