<?php

use Symfony\Component\Yaml\Yaml;
use SSNepenthe\Hermes\Scraper\ScraperFactory;

class ScrapersTest extends ScraperTestCase
{
    /** @test */
    function it_uses_full_crawler_if_no_selector_provided()
    {
        $scraper = ScraperFactory::fromConfigFile(
            $this->getScraperFixturePath('no-selector.php')
        );
        $expectedResult = Yaml::parse(
            file_get_contents($this->getResultsFixturePath('no-selector.yml'))
        );
        $crawler = $this->makeCrawlerFor('https://duckduckgo.com/html?q=firefox');

        $this->assertEquals($expectedResult, $scraper->scrape($crawler));
    }

    /** @test */
    function it_can_perform_a_single_level_scrape()
    {
        $scraper = ScraperFactory::fromConfigFile(
            $this->getScraperFixturePath('one-deep.php')
        );
        $expectedResult = Yaml::parse(
            file_get_contents($this->getResultsFixturePath('one-deep.yml'))
        );
        $crawler = $this->makeCrawlerFor('https://duckduckgo.com/html?q=firefox');

        $this->assertEquals($expectedResult, $scraper->scrape($crawler));
    }

    /** @test */
    function it_can_differentiate_between_singular_and_plural_data_points()
    {
        $scraper = ScraperFactory::fromConfigFile(
            $this->getScraperFixturePath('one-deep-plural.php')
        );
        $expectedResult = Yaml::parse(
            file_get_contents($this->getResultsFixturePath('one-deep-plural.yml'))
        );
        $crawler = $this->makeCrawlerFor('https://duckduckgo.com/html?q=firefox');

        $this->assertEquals($expectedResult, $scraper->scrape($crawler));
    }

    /** @test */
    function it_can_perform_a_multi_level_scrape()
    {
        $scraper = ScraperFactory::fromConfigFile(
            $this->getScraperFixturePath('two-deep.php')
        );
        $expectedResult = Yaml::parse(
            file_get_contents($this->getResultsFixturePath('two-deep.yml'))
        );
        $crawler = $this->makeCrawlerFor('https://duckduckgo.com/html?q=firefox');

        $this->assertEquals($expectedResult, $scraper->scrape($crawler));
    }

    /** @test */
    function it_can_perform_a_multi_point_scrape()
    {
        $scraper = ScraperFactory::fromConfigFile(
            $this->getScraperFixturePath('multi-point.php')
        );
        $expectedResult = Yaml::parse(
            file_get_contents($this->getResultsFixturePath('multi-point.yml'))
        );
        $crawler = $this->makeCrawlerFor('https://duckduckgo.com/html?q=firefox');

        $this->assertEquals($expectedResult, $scraper->scrape($crawler));
    }
}
