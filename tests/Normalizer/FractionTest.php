<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Normalizer\Fraction;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;

class FractionTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable_and_callable()
    {
        $n = new Fraction;

        $this->assertInstanceOf(NormalizerInterface::class, $n);
        $this->assertTrue(is_callable($n));
    }

    /** @test */
    function it_replaces_fractions_with_numeric_counterparts()
    {
        $n = new Fraction;
        $s = '⅛ ⅜ ⅝ ⅞ ⅙ ⅚ ⅕ ⅖ ⅗ ⅘ ¼ ¾ ⅓ ⅔ ½';
        $c = Mockery::mock(Crawler::class);

        $this->assertEquals(
            ['1/8 3/8 5/8 7/8 1/6 5/6 1/5 2/5 3/5 4/5 1/4 3/4 1/3 2/3 1/2'],
            $n($s, $c)
        );
    }
}
