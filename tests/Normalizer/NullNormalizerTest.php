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
    function it_is_instantiable()
    {
        $normalizer = new NullNormalizer;

        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
    }

    /** @test */
    function it_returns_the_same_value_it_is_given()
    {
        $normalizer = new NullNormalizer;
        $crawler = Mockery::mock(Crawler::class);

        $this->assertEquals(['value'], $normalizer->normalize(['value'], $crawler));
    }
}
