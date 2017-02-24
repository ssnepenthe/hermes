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
    function it_is_instantiable_and_callable()
    {
        $n = new VerticalSpace;

        $this->assertInstanceOf(NormalizerInterface::class, $n);
        $this->assertTrue(is_callable($n));
    }

    /** @test */
    function it_replaces_all_vertical_space_characters_with_php_eol()
    {
        $n = new VerticalSpace;
        // Only testing the basics, trust PCRE "\h" implementation for the rest.
        $s = "one\r\ntwo\rthree\nend";
        $c = Mockery::mock(Crawler::class);

        $this->assertEquals(
            [sprintf('one%1$stwo%1$sthree%1$send', PHP_EOL)],
            $n($s, $c)
        );
    }
}
