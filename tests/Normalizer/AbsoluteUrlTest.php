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
    function it_is_instantiable()
    {
        $normalizer = new AbsoluteUrl;

        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
    }

    /** @test */
    function it_bails_if_given_non_url()
    {
        $normalizer = new AbsoluteUrl;
        $crawler = Mockery::mock(Crawler::class)
            ->shouldReceive('getUri')
            ->andReturn('https://example.com')
            ->once()
            ->mock();

        $this->assertEquals(
            ['just a regular string'],
            $normalizer->normalize(['just a regular string'], $crawler)
        );
    }

    /** @test */
    function it_fixes_schemeless_urls()
    {
        $normalizer = new AbsoluteUrl;
        $crawler = Mockery::mock(Crawler::class)
            ->shouldReceive('getUri')
            ->andReturn('https://example.com')
            ->once()
            ->mock();

        $this->assertEquals(
            ['https://example.com/path/to/image.jpg'],
            $normalizer->normalize(['//example.com/path/to/image.jpg'], $crawler)
        );
    }

    /** @test */
    function it_fixes_hostless_urls()
    {
        $normalizer = new AbsoluteUrl;
        $crawler = Mockery::mock(Crawler::class)
            ->shouldReceive('getUri')
            ->andReturn('https://example.com')
            ->once()
            ->mock();

        $this->assertEquals(
            ['https://example.com/path/to/image.jpg'],
            $normalizer->normalize(['/path/to/image.jpg'], $crawler)
        );
    }
}
