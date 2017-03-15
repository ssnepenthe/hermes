<?php

use Symfony\Component\Yaml\Yaml;
use SSNepenthe\Hermes\Scraper\ScraperFactory;

class ExtractorsTest extends ScraperTestCase
{
    /** @test */
    function it_can_extract_values_from_all_nodes()
    {
        $scraper = ScraperFactory::fromConfigFile(
            $this->getScraperFixturePath('all-extractor.php')
        );
        $expectedResult = Yaml::parse(
            file_get_contents($this->getResultsFixturePath('all-extractor.yml'))
        );
        $crawler = $this->makeCrawlerFor('https://duckduckgo.com/html?q=firefox');

        $this->assertEquals($expectedResult, $scraper->scrape($crawler));
    }

    /** @test */
    function it_can_extract_values_from_children_of_all_nodes()
    {
        $scraper = ScraperFactory::fromConfigFile(
            $this->getScraperFixturePath('all-from-children-extractor.php')
        );
        $expectedResult = Yaml::parse(
            file_get_contents(
                $this->getResultsFixturePath('all-from-children-extractor.yml')
            )
        );
        $crawler = $this->makeCrawlerFor(
            'http://spryliving.com/recipes/grilled-salmon-with-pesto/'
        );

        $this->assertEquals($expectedResult, $scraper->scrape($crawler));
    }

    /** @test */
    function it_can_extract_and_split_values_from_all_nodes()
    {
        $scraper = ScraperFactory::fromConfigFile(
            $this->getScraperFixturePath('all-split.php')
        );
        $expectedResult = Yaml::parse(
            file_get_contents(
                $this->getResultsFixturePath('all-split.yml')
            )
        );
        $crawler = $this->makeCrawlerFor('https://duckduckgo.com/html?q=firefox');

        $this->assertEquals($expectedResult, $scraper->scrape($crawler));
    }

    /** @test */
    function it_can_extract_and_split_values_from_first_node()
    {
        $scraper = ScraperFactory::fromConfigFile(
            $this->getScraperFixturePath('first-split.php')
        );
        $expectedResult = Yaml::parse(
            file_get_contents(
                $this->getResultsFixturePath('first-split.yml')
            )
        );
        $crawler = $this->makeCrawlerFor('https://duckduckgo.com/html?q=firefox');

        $this->assertEquals($expectedResult, $scraper->scrape($crawler));
    }

    /** @test */
    function it_can_extract_value_from_first_node()
    {
        $scraper = ScraperFactory::fromConfigFile(
            $this->getScraperFixturePath('first-extractor.php')
        );
        $expectedResult = Yaml::parse(
            file_get_contents($this->getResultsFixturePath('first-extractor.yml'))
        );
        $crawler = $this->makeCrawlerFor('https://duckduckgo.com/html?q=firefox');

        $this->assertEquals($expectedResult, $scraper->scrape($crawler));
    }

    /** @test */
    function it_can_extract_value_from_children_of_first_node()
    {
        $scraper = ScraperFactory::fromConfigFile(
            $this->getScraperFixturePath('first-from-children-extractor.php')
        );
        $expectedResult = Yaml::parse(
            file_get_contents(
                $this->getResultsFixturePath('first-from-children-extractor.yml')
            )
        );
        $crawler = $this->makeCrawlerFor(
            'http://spryliving.com/recipes/grilled-salmon-with-pesto/'
        );

        $this->assertEquals($expectedResult, $scraper->scrape($crawler));
    }
}
