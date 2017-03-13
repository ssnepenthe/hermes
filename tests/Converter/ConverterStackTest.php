<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Converter\ConverterStack;
use SSNepenthe\Hermes\Converter\ConverterInterface;

class ConverterStackTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $converter = new ConverterStack;

        $this->assertInstanceOf(ConverterInterface::class, $converter);
    }

    /** @test */
    function it_can_add_converters()
    {
        $c1 = Mockery::mock(ConverterInterface::class);
        $converter = new ConverterStack;
        $converter->addConverter($c1);

        $this->assertEquals([$c1], $converter->getConverters());
    }

    /** @test */
    function it_delegates_to_stack_to_perform_conversion()
    {
        $c1 = Mockery::mock(ConverterInterface::class)
            ->shouldReceive('convert')
            ->once()
            ->andReturnUsing(function ($values) {
                return array_map(function ($value) {
                    return $value . ' 1';
                }, $values);
            })
            ->mock();
        $c2 = Mockery::mock(ConverterInterface::class)
            ->shouldReceive('convert')
            ->once()
            ->andReturnUsing(function ($values) {
                return array_map(function ($value) {
                    return $value . ' 2';
                }, $values);
            })
            ->mock();
        $crawler = Mockery::mock(Crawler::class);
        $converter = new ConverterStack([$c1, $c2]);

        $this->assertEquals(
            ['Converted 1 2'],
            $converter->convert(['Converted'], $crawler)
        );
    }
}
