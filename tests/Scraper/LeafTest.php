<?php

use SSNepenthe\Hermes\Scraper\Leaf;
use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Matcher\MatcherInterface;
use SSNepenthe\Hermes\Scraper\ScraperInterface;
use SSNepenthe\Hermes\Converter\ConverterInterface;
use SSNepenthe\Hermes\Extractor\ExtractorInterface;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;

class LeafTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $matcher = Mockery::mock(MatcherInterface::class);
        $extractor = Mockery::mock(ExtractorInterface::class);
        $normalizer = Mockery::mock(NormalizerInterface::class);
        $converter = Mockery::mock(ConverterInterface::class);
        $scraper = new Leaf(
            'name',
            'type',
            $matcher,
            $extractor,
            $normalizer,
            $converter
        );

        $this->assertInstanceOf(ScraperInterface::class, $scraper);
    }

    /** @test */
    function it_delegates_to_matcher_to_check_crawler_for_match()
    {
        $matcher = Mockery::mock(MatcherInterface::class)
            ->shouldReceive('matches')
            ->twice()
            ->andReturn(true, false)
            ->mock();
        $extractor = Mockery::mock(ExtractorInterface::class);
        $normalizer = Mockery::mock(NormalizerInterface::class);
        $converter = Mockery::mock(ConverterInterface::class);
        $crawler = Mockery::mock(Crawler::class);
        $scraper = new Leaf(
            'name',
            'type',
            $matcher,
            $extractor,
            $normalizer,
            $converter
        );

        $this->assertTrue($scraper->matches($crawler));
        $this->assertFalse($scraper->matches($crawler));
    }
}
