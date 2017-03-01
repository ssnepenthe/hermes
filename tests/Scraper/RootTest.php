<?php

use SSNepenthe\Hermes\Scraper\Root;
use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Matcher\MatcherInterface;
use SSNepenthe\Hermes\Scraper\ScraperInterface;

class RootTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $matcher = Mockery::mock(MatcherInterface::class);
        $scraper = new Root('name', $matcher);

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
        $crawler = Mockery::mock(Crawler::class);
        $scraper = new Root('name', $matcher);

        $this->assertTrue($scraper->matches($crawler));
        $this->assertFalse($scraper->matches($crawler));
    }
}
