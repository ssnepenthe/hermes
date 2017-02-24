<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Normalizer\NullNormalizer;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;

class NullNormalizerTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable_and_callable()
    {
        $n = new NullNormalizer;

        $this->assertInstanceOf(NormalizerInterface::class, $n);
        $this->assertTrue(is_callable($n));
    }

    /** @test */
    function it_returns_the_same_value_it_is_given_as_an_array()
    {
        $n = new NullNormalizer;
        $c = Mockery::mock(Crawler::class);

        $this->assertEquals(['value'], $n(['value'], $c));
        $this->assertEquals(['value'], $n('value', $c));
    }
}
