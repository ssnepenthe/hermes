<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Normalizer\VerticalSpace;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;

class VerticalSpaceTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $normalizer = new VerticalSpace;

        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
    }

    /** @test */
    function it_replaces_all_vertical_space_characters_with_php_eol()
    {
        $normalizer = new VerticalSpace;
        // Only testing the basics, trust PCRE "\h" implementation for the rest.
        $string = "one\r\ntwo\rthree\nend";
        $crawler = Mockery::mock(Crawler::class);

        $this->assertEquals(
            [sprintf('one%1$stwo%1$sthree%1$send', PHP_EOL)],
            $normalizer->normalize($string, $crawler)
        );
    }
}
