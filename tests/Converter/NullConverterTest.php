<?php

use SSNepenthe\Hermes\Converter\NullConverter;
use SSNepenthe\Hermes\Converter\ConverterInterface;

class NullConverterTest extends PHPUnit\Framework\TestCase
{
    /** @test */
    function it_is_instantiable_and_callable()
    {
        $c = new NullConverter;

        $this->assertInstanceOf(ConverterInterface::class, $c);
        $this->assertTrue(is_callable($c));
    }

    /** @test */
    function it_returns_the_same_value_it_is_given_as_an_array()
    {
        $c = new NullConverter;

        $this->assertEquals(['value'], $c(['value']));
        $this->assertEquals(['value'], $c('value'));
    }
}
