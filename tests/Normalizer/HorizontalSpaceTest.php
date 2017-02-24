<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Normalizer\HorizontalSpace;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;

class HorizontalSpaceTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable_and_callable()
    {
        $n = new HorizontalSpace;

        $this->assertInstanceOf(NormalizerInterface::class, $n);
        $this->assertTrue(is_callable($n));
    }

    /** @test */
    function it_replaces_all_horizontal_space_characters_with_ordinary_space()
    {
        $n = new HorizontalSpace;
        // Only testing the basics, trust PCRE "\h" implementation for the rest.
        $s = "space tab\tend";
        $c = Mockery::mock(Crawler::class);

        $this->assertEquals(['space tab end'], $n($s, $c));
    }
}
