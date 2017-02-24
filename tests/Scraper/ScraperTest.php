<?php

use Symfony\Component\Yaml\Yaml;
use SSNepenthe\Hermes\Scraper\Scraper;
use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Scraper\ScraperFactory;
use SSNepenthe\Hermes\Matcher\MatcherInterface;
use SSNepenthe\Hermes\Scraper\ScraperInterface;

class ScraperTest extends PHPUnit\Framework\TestCase
{
    protected $testDataPath;

    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $matcher = Mockery::mock(MatcherInterface::class);
        $scraper = new Scraper([], $matcher, [], 'name');

        $this->assertInstanceOf(ScraperInterface::class, $scraper);
    }

    /** @test */
    function it_delegates_to_matcher_to_check_crawler_for_match()
    {
        $matcher = Mockery::mock(MatcherInterface::class)
            ->shouldReceive('matches')
            ->once()
            ->andReturn(true)
            ->mock();
        $crawler = Mockery::mock(Crawler::class);
        $scraper = new Scraper([], $matcher, [], 'name');

        $this->assertTrue($scraper->matches($crawler));
    }

    /** @test */
    function it_uses_full_crawler_if_no_selector_provided()
    {

    }

    /** @test */
    function it_can_perform_a_single_level_scrape()
    {
        $testUrl = 'https://duckduckgo.com/html?q=chrome';

        $this->assertEquals(
            $this->getExpectedScrapeResult($testUrl),
            $this->getActualScrapeResult($testUrl)
        );
    }

    /** @test */
    function it_can_perform_a_multi_level_scrape()
    {
        $testUrl = 'https://duckduckgo.com/html?q=safari';

        $this->assertEquals(
            $this->getExpectedScrapeResult($testUrl),
            $this->getActualScrapeResult($testUrl)
        );
    }

    /** @test */
    function it_can_perform_a_multi_data_point_scrape()
    {
        $testUrl = 'https://duckduckgo.com/html?q=firefox';

        $this->assertEquals(
            $this->getExpectedScrapeResult($testUrl),
            $this->getActualScrapeResult($testUrl)
        );
    }

    /** @test */
    function it_returns_first_result_if_it_is_only_result_and_not_array()
    {

    }

    // ======================================================================= //
    // =============================== HELPERS =============================== //
    // ======================================================================= //

    protected function getActualScrapeResult($url)
    {
        $crawler = $this->makeCrawler($url);
        $scraper = $this->makeScraper($url);

        if (! ($crawler && $scraper)) {
            return [];
        }

        return $scraper->scrape($crawler);
    }

    protected function getExpectedScrapeResult($url)
    {
        $file = $this->getResultsFile($url);

        if (! is_readable($file)) {
            return [];
        }

        return Yaml::parse(file_get_contents($file));
    }

    protected function getHtmlFile($url)
    {
        $file = 'html' . DIRECTORY_SEPARATOR . $this->urlToFile($url) . '.html';

        return $this->getTestDataFile($file);
    }

    protected function getResultsFile($url)
    {
        $file = 'results' . DIRECTORY_SEPARATOR . $this->urlToFile($url) . '.yml';

        return $this->getTestDataFile($file);
    }

    protected function getScrapersFile($url)
    {
        $file = 'scrapers' . DIRECTORY_SEPARATOR . $this->urlToFile($url) . '.php';

        return $this->getTestDataFile($file);
    }

    protected function getTestDataFile($file)
    {
        if (is_null($this->testDataPath)) {
            $this->testDataPath = realpath(__DIR__ . '/../_data');
        }

        return $this->testDataPath . DIRECTORY_SEPARATOR . $file;
    }

    protected function makeCrawler($url)
    {
        $file = $this->getHtmlFile($url);

        if (! is_readable($file)) {
            return false;
        }

        return new Crawler(file_get_contents($file), $url);
    }

    protected function makeScraper($url)
    {
        $file = $this->getScrapersFile($url);

        if (! is_readable($file)) {
            return false;
        }

        return ScraperFactory::fromConfigFile($file);
    }

    protected function urlToFile($url)
    {
        return preg_replace('/[^a-zA-Z0-9]/', '-', $url);
    }
}
