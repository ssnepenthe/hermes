<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Converter\ToInterval;
use SSNepenthe\Hermes\Converter\ConverterInterface;

class ToIntervalTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $c = new ToInterval;

        $this->assertInstanceOf(ConverterInterface::class, $c);
    }

    /** @test */
    function it_converts_standard_dateinterval_spec_string()
    {
        $converter = new ToInterval;
        $crawler = Mockery::mock(Crawler::class);

        $this->assertEquals(
            [new DateInterval('PT30M')],
            $converter->convert(['PT30M'], $crawler)
        );
    }

    /** @test */
    function it_converts_relative_dateinterval_strings()
    {
        $converter = new ToInterval;
        $crawler = Mockery::mock(Crawler::class);

        $this->assertEquals(
            [new DateInterval('PT30M')],
            $converter->convert(['30 minutes'], $crawler)
        );
    }

    /** @test */
    function it_returns_unchanged_value_if_dateinterval_string_is_bad()
    {
        $converter = new ToInterval;
        $crawler = Mockery::mock(Crawler::class);

        $this->assertEquals(
            ['not interval'],
            $converter->convert(['not interval'], $crawler)
        );
    }
}
