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
    function it_is_instantiable()
    {
        $normalizer = new ConsecutiveHorizontalSpace;

        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
    }

    /** @test */
    function it_reduces_multiple_horizontal_spaces_to_single_space()
    {
        $normalizer = new ConsecutiveHorizontalSpace;
        $strings = ["one two  three   four    five     end"];
        $crawler = Mockery::mock(Crawler::class);

        $this->assertEquals(
            ["one two three four five end"],
            $normalizer->normalize($strings, $crawler)
        );
    }

    /** @test */
    function it_disregards_vertical_spaces()
    {
        $normalizer = new ConsecutiveHorizontalSpace;
        $strings = ["one\ntwo\n\nthree\n\n\nfour\n\n\n\nfive\n\n\n\n\nend"];
        $crawler = Mockery::mock(Crawler::class);

        $this->assertEquals($strings, $normalizer->normalize($strings, $crawler));
    }
}
