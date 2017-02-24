<?php

use SSNepenthe\Hermes\Converter\ToInterval;
use SSNepenthe\Hermes\Converter\ConverterInterface;

class ToIntervalTest extends PHPUnit\Framework\TestCase
{
    /** @test */
    function it_is_instantiable_and_callable()
    {
        $c = new ToInterval;

        $this->assertInstanceOf(ConverterInterface::class, $c);
        $this->assertTrue(is_callable($c));
    }

    /** @test */
    function it_converts_standard_dateinterval_spec_string()
    {
        $c = new ToInterval;

        $this->assertEquals([new DateInterval('PT30M')], $c->convert(['PT30M']));
    }

    /** @test */
    function it_converts_relative_dateinterval_strings()
    {
        $c = new ToInterval;

        $this->assertEquals(
            [new DateInterval('PT30M')],
            $c->convert(['30 minutes'])
        );
    }

    /** @test */
    function it_returns_unchanged_value_if_dateinterval_string_is_bad()
    {
        $c = new ToInterval;

        $this->assertEquals(['not interval'], $c->convert(['not interval']));
    }
}
