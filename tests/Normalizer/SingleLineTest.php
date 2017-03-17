<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Normalizer\SingleLine;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;

class SingleLineTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $normalizer = new SingleLine;

        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
    }

    /** @test */
    function it_converts_a_multi_line_string_to_single_line()
    {
        $normalizer = new SingleLine;
        $crawler = Mockery::mock(Crawler::class);

        $this->assertEquals(
            ['one two'],
            $normalizer->normalize(["one" . PHP_EOL . "two"], $crawler)
        );
    }
}
