<?php

use SSNepenthe\Hermes\Scraper\ScraperFactory;

class MatchersTest extends ScraperTestCase
{
    /** @test */
    function it_can_match_against_a_crawler_by_selector()
    {
        $scraper = ScraperFactory::fromConfigFile(
            $this->getScraperFixturePath('selector-matcher.php')
        );
        $crawler = $this->makeCrawlerFor('https://duckduckgo.com/html?q=firefox');

        $this->assertTrue($scraper->matches($crawler));
    }
}
