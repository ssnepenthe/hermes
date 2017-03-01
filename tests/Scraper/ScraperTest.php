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

    protected function getTestDataFile($file)
    {
        if (is_null($this->testDataPath)) {
            $this->testDataPath = realpath(__DIR__ . '/../fixtures');
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
        if (false !== strpos($url, 'chrome')) {
            return $this->makeDdgChromeScraper();
        }

        if (false !== strpos($url, 'firefox')) {
            return $this->makeDdgFirefoxScraper();
        }

        if (false !== strpos($url, 'safari')) {
            return $this->makeDdgSafariScraper();
        }

        throw new RuntimeException('Scraper not found...');
    }

    protected function makeDdgChromeScraper()
    {
        return new SSNepenthe\Hermes\Scraper\Root(
            'ddg.gg',
            new SSNepenthe\Hermes\Matcher\NullMatcher,
            null,
            [
                new SSNepenthe\Hermes\Scraper\Leaf(
                    'icon',
                    new SSNepenthe\Hermes\Matcher\NullMatcher,
                    new SSNepenthe\Hermes\Extractor\All('href'),
                    new SSNepenthe\Hermes\Normalizer\NullNormalizer,
                    new SSNepenthe\Hermes\Converter\NullConverter,
                    '[rel="apple-touch-icon"]'
                ),
            ]
        );
    }

    protected function makeDdgFirefoxScraper()
    {
        return new SSNepenthe\Hermes\Scraper\Root(
            'ddg.gg',
            new SSNepenthe\Hermes\Matcher\NullMatcher,
            null,
            [
                new SSNepenthe\Hermes\Scraper\Branch(
                    'results',
                    new SSNepenthe\Hermes\Matcher\NullMatcher,
                    '.web-result',
                    [
                        new SSNepenthe\Hermes\Scraper\Leaf(
                            'title',
                            new SSNepenthe\Hermes\Matcher\NullMatcher,
                            new SSNepenthe\Hermes\Extractor\All('_text'),
                            new SSNepenthe\Hermes\Normalizer\NormalizerStack([
                                new SSNepenthe\Hermes\Normalizer\HorizontalSpace,
                                new SSNepenthe\Hermes\Normalizer\VerticalSpace
                            ]),
                            new SSNepenthe\Hermes\Converter\NullConverter,
                            '.result__title'
                        ),
                        new SSNepenthe\Hermes\Scraper\Leaf(
                            'url',
                            new SSNepenthe\Hermes\Matcher\NullMatcher,
                            new SSNepenthe\Hermes\Extractor\All('href'),
                            new SSNepenthe\Hermes\Normalizer\NullNormalizer,
                            new SSNepenthe\Hermes\Converter\NullConverter,
                            '.result__url'
                        ),
                        new SSNepenthe\Hermes\Scraper\Leaf(
                            'description',
                            new SSNepenthe\Hermes\Matcher\NullMatcher,
                            new SSNepenthe\Hermes\Extractor\All('_text'),
                            new SSNepenthe\Hermes\Normalizer\NullNormalizer,
                            new SSNepenthe\Hermes\Converter\NullConverter,
                            '.result__snippet'
                        ),
                    ]
                ),
            ]
        );
    }

    protected function makeDdgSafariScraper()
    {
        return new SSNepenthe\Hermes\Scraper\Root(
            'ddg.gg',
            new SSNepenthe\Hermes\Matcher\NullMatcher,
            null,
            [
                new SSNepenthe\Hermes\Scraper\Branch(
                    'header',
                    new SSNepenthe\Hermes\Matcher\NullMatcher,
                    '.header__form',
                    [
                        new SSNepenthe\Hermes\Scraper\Leaf(
                            'input',
                            new SSNepenthe\Hermes\Matcher\NullMatcher,
                            new SSNepenthe\Hermes\Extractor\All('value'),
                            new SSNepenthe\Hermes\Normalizer\NormalizerStack([
                                new SSNepenthe\Hermes\Normalizer\HorizontalSpace,
                                new SSNepenthe\Hermes\Normalizer\VerticalSpace
                            ]),
                            new SSNepenthe\Hermes\Converter\NullConverter,
                            '.search__input'
                        ),
                    ]
                )
            ]
        );
    }

    protected function urlToFile($url)
    {
        return preg_replace('/[^a-zA-Z0-9]/', '-', $url);
    }
}
