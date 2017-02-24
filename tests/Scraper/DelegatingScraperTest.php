<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Scraper\ScraperInterface;
use SSNepenthe\Hermes\Scraper\DelegatingScraper;
use SSNepenthe\Hermes\Scraper\ScraperResolverInterface;

class DelegatingScraperTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $resolver = Mockery::mock(ScraperResolverInterface::class);
        $scraper = new DelegatingScraper($resolver);

        $this->assertInstanceOf(ScraperInterface::class, $scraper);
    }

    /** @test */
    function it_delegates_to_resolver_to_check_for_match()
    {
        $crawler = Mockery::mock(Crawler::class);
        $s1 = Mockery::mock(ScraperInterface::class);
        $resolver = Mockery::mock(ScraperResolverInterface::class)
            ->shouldReceive('resolve')
            ->twice()
            ->andReturn($s1, false)
            ->mock();
        $scraper = new DelegatingScraper($resolver);

        $this->assertTrue($scraper->matches($crawler));
        $this->assertFalse($scraper->matches($crawler));
    }

    /** @test */
    function it_throws_if_no_matching_scraper_is_found()
    {
        $this->expectException(RuntimeException::class);

        $crawler = Mockery::mock(Crawler::class);
        $resolver = Mockery::mock(ScraperResolverInterface::class)
            ->shouldReceive('resolve')
            ->once()
            ->andReturn(false)
            ->mock();
        $scraper = new DelegatingScraper($resolver);

        $scraper->scrape($crawler);
    }

    /** @test */
    function it_delegates_to_scraper_to_scrape_crawler()
    {
        $crawler = Mockery::mock(Crawler::class);
        $s1 = Mockery::mock(ScraperInterface::class)
            ->shouldReceive('scrape')
            ->once()
            ->andReturn(['scraped value'])
            ->mock();
        $resolver = Mockery::mock(ScraperResolverInterface::class)
            ->shouldReceive('resolve')
            ->once()
            ->andReturn($s1)
            ->mock();
        $scraper = new DelegatingScraper($resolver);

        $this->assertEquals(['scraped value'], $scraper->scrape($crawler));
    }
}
