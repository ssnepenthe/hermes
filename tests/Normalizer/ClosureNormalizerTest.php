<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Normalizer\ClosureNormalizer;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;

class ClosureNormalizerTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $normalizer = new ClosureNormalizer(function () {});

        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
    }

    /** @test */
    function it_delegates_to_the_passed_closure_to_perform_normalization()
    {
        $normalizer = new ClosureNormalizer(function ($v, $c) {
            return $v . 'z';
        });
        $crawler = Mockery::mock(Crawler::class);

        $this->assertEquals(
            ['just a regular stringz'],
            $normalizer->normalize('just a regular string', $crawler)
        );
    }
}
