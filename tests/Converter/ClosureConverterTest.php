<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Converter\ClosureConverter;
use SSNepenthe\Hermes\Converter\ConverterInterface;

class ClosureConverterTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $converter = new ClosureConverter(function () {});

        $this->assertInstanceOf(ConverterInterface::class, $converter);
    }

    /** @test */
    function it_delegates_to_passed_closure_to_perform_conversion()
    {
        $converter = new ClosureConverter(function ($values) {
            return array_map(function ($value) {
                return 'Converted ' . $value;
            }, (array) $values);
        });
        $crawler = Mockery::mock(Crawler::class);

        $this->assertEquals(
            ['Converted Value'],
            $converter->convert('Value', $crawler)
        );
    }
}
