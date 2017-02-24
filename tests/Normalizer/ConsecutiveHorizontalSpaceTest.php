<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;
use SSNepenthe\Hermes\Normalizer\ConsecutiveHorizontalSpace;

class ConsecutiveHorizontalSpaceTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable_and_callable()
    {
        $n = new ConsecutiveHorizontalSpace;

        $this->assertInstanceOf(NormalizerInterface::class, $n);
        $this->assertTrue(is_callable($n));
    }

    /** @test */
    function it_reduces_multiple_horizontal_spaces_to_single_space()
    {
        $n = new ConsecutiveHorizontalSpace;
        $s = "one two  three   four    five     end";
        $c = Mockery::mock(Crawler::class);

        $this->assertEquals(["one two three four five end"], $n($s, $c));
    }

    /** @test */
    function it_disregards_multiple_vertical_spaces()
    {
        $n = new ConsecutiveHorizontalSpace;
        $s = "one\ntwo\n\nthree\n\n\nfour\n\n\n\nfive\n\n\n\n\nend";
        $c = Mockery::mock(Crawler::class);

        $this->assertEquals([$s], $n($s, $c));
    }
}
