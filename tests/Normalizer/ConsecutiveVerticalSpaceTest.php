<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;
use SSNepenthe\Hermes\Normalizer\ConsecutiveVerticalSpace;

class ConsecutiveVerticalSpaceTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $normalizer = new ConsecutiveVerticalSpace;

        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
    }

    /** @test */
    function it_reduces_multiple_vertical_spaces_to_single_space()
    {
        $normalizer = new ConsecutiveVerticalSpace;
        $strings = ["one\ntwo\n\nthree\n\n\nfour\n\n\n\nfive\n\n\n\n\nend"];
        $crawler = Mockery::mock(Crawler::class);

        $this->assertEquals(
            ["one\ntwo\nthree\nfour\nfive\nend"],
            $normalizer->normalize($strings, $crawler)
        );
    }

    /** @test */
    function it_disregards_horizontal_spaces()
    {
        $normalizer = new ConsecutiveVerticalSpace;
        $strings = ["one two  three   four    five     end"];
        $crawler = Mockery::mock(Crawler::class);

        $this->assertEquals($strings, $normalizer->normalize($strings, $crawler));
    }
}
