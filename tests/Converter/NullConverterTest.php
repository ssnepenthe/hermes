<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Converter\NullConverter;
use SSNepenthe\Hermes\Converter\ConverterInterface;

class NullConverterTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $converter = new NullConverter;

        $this->assertInstanceOf(ConverterInterface::class, $converter);
    }

    /** @test */
    function it_returns_the_same_value_it_is_given_as_an_array()
    {
        $converter = new NullConverter;
        $crawler = Mockery::mock(Crawler::class);

        $this->assertEquals(['value'], $converter->convert(['value'], $crawler));
        $this->assertEquals(['value'], $converter->convert('value', $crawler));
    }
}
