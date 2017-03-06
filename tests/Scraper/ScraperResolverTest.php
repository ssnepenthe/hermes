<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Scraper\ScraperResolver;
use SSNepenthe\Hermes\Matcher\MatcherInterface;
use SSNepenthe\Hermes\Scraper\ScraperInterface;
use SSNepenthe\Hermes\Scraper\ScraperResolverInterface;

class ScraperResolverTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $resolver = new ScraperResolver;

        $this->assertInstanceOf(ScraperResolverInterface::class, $resolver);
    }

    /** @test */
    function it_can_add_scrapers()
    {
        $matcher = Mockery::mock(MatcherInterface::class)
            ->shouldReceive('getType')
            ->once()
            ->andReturn('selector')
            ->mock();
        $scraper = Mockery::mock(ScraperInterface::class)
            ->shouldReceive('getMatcher')
            ->once()
            ->andReturn($matcher)
            ->mock();
        $resolver = new ScraperResolver;
        $resolver->addScraper($scraper);

        $this->assertEquals([
            'url' => [],
            'host' => [],
            'title' => [],
            'selector' => [$scraper],
            'document' => [],
        ], $resolver->getScrapers());
    }

    /** @test */
    function it_can_add_scrapers_of_unexpected_type()
    {
        $matcher = Mockery::mock(MatcherInterface::class)
            ->shouldReceive('getType')
            ->once()
            ->andReturn('fake')
            ->mock();
        $scraper = Mockery::mock(ScraperInterface::class)
            ->shouldReceive('getMatcher')
            ->once()
            ->andReturn($matcher)
            ->mock();
        $resolver = new ScraperResolver;
        $resolver->addScraper($scraper);

        $this->assertEquals([
            'url' => [],
            'host' => [],
            'title' => [],
            'selector' => [],
            'document' => [],
            'fake' => [$scraper],
        ], $resolver->getScrapers());
    }

    /** @test */
    function it_can_get_a_single_type_of_scraper()
    {
        $matcher = Mockery::mock(MatcherInterface::class)
            ->shouldReceive('getType')
            ->once()
            ->andReturn('url')
            ->mock();
        $scraper = Mockery::mock(ScraperInterface::class)
            ->shouldReceive('getMatcher')
            ->once()
            ->andReturn($matcher)
            ->mock();
        $resolver = new ScraperResolver;
        $resolver->addScraper($scraper);

        $this->assertEquals([$scraper], $resolver->getScrapers('url'));
        $this->assertEquals([], $resolver->getScrapers('host'));
        $this->assertEquals([], $resolver->getScrapers('doesntexist'));
    }

    /** @test */
    function it_loops_through_all_scrapers_until_it_finds_a_match()
    {
        $crawler = Mockery::mock(Crawler::class);
        // MatcherInterface::getType() mock is unreliable, possibly b/c it is static.
        $matcher = Mockery::mock(MatcherInterface::class)
            ->shouldReceive('getType')
            ->twice()
            ->andReturn('url', 'host')
            ->mock();
        $s1 = Mockery::mock(ScraperInterface::class)
            ->shouldReceive(['getMatcher' => $matcher, 'matches' => false])
            ->once()
            ->mock();
        $s2 = Mockery::mock(ScraperInterface::class)
            ->shouldReceive(['getMatcher' => $matcher, 'matches' => true])
            ->once()
            ->mock();
        $resolver = new ScraperResolver([$s1, $s2]);

        $this->assertSame($s2, $resolver->resolve($crawler));
    }

    /** @test */
    function it_checks_scrapers_in_order_based_on_type()
    {
        $crawler = Mockery::mock(Crawler::class);
        $matcher = Mockery::mock(MatcherInterface::class)
            ->shouldReceive('getType')
            ->twice()
            ->andReturn('host', 'url')
            ->mock();
        $s1 = Mockery::mock(ScraperInterface::class)
            ->shouldReceive(['getMatcher' => $matcher])
            ->once()
            ->mock();
        $s2 = Mockery::mock(ScraperInterface::class)
            ->shouldReceive(['getMatcher' => $matcher, 'matches' => true])
            ->once()
            ->mock();
        $resolver = new ScraperResolver([$s1, $s2]);

        // $s2 was added second but url match is higher priority that host match.
        $this->assertSame($s2, $resolver->resolve($crawler));
    }

    /** @test */
    function it_returns_false_if_no_matching_scraper_found()
    {
        // @todo Should this return false, null or throw?
        $crawler = Mockery::mock(Crawler::class);
        $matcher = Mockery::mock(MatcherInterface::class)
            ->shouldReceive(['getType' => 'url'])
            ->mock();
        $s1 = Mockery::mock(ScraperInterface::class)
            ->shouldReceive(['getMatcher' => $matcher, 'matches' => false])
            ->once()
            ->mock();
        $resolver = new ScraperResolver([$s1]);

        $this->assertFalse($resolver->resolve($crawler));
    }
}
