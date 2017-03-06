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
    function it_is_instantiable()
    {
        $normalizer = new HorizontalSpace;

        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
    }

    /** @test */
    function it_replaces_all_horizontal_space_characters_with_ordinary_space()
    {
        $normalizer = new HorizontalSpace;
        // Only testing the basics, trust PCRE "\h" implementation for the rest.
        $string = "space tab\tend";
        $crawler = Mockery::mock(Crawler::class);

        $this->assertEquals(
            ['space tab end'],
            $normalizer->normalize($string, $crawler)
        );
    }
}
