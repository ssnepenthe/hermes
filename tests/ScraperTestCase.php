<?php

use Symfony\Component\DomCrawler\Crawler;

abstract class ScraperTestCase extends PHPUnit\Framework\TestCase
{
    protected function getBaseFixturePath() : string
    {
        return realpath(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures');
    }

    protected function getFixturePath(string $file) : string
    {
        return $this->getBaseFixturePath() . DIRECTORY_SEPARATOR . $file;
    }

    protected function getHtmlFixturePath(string $url)
    {
        $file = 'html' . DIRECTORY_SEPARATOR . $this->urlToFileName($url) . '.html';

        return $this->getFixturePath($file);
    }

    protected function getResultsFixturePath(string $file)
    {
        return $this->getFixturePath('results' . DIRECTORY_SEPARATOR . $file);
    }

    protected function getScraperFixturePath(string $file)
    {
        return $this->getFixturePath('scrapers' . DIRECTORY_SEPARATOR . $file);
    }

    protected function makeCrawlerFor(string $url)
    {
        $file = $this->getHtmlFixturePath($url);

        if (! is_readable($file)) {
            throw new InvalidArgumentException;
        }

        return new Crawler(file_get_contents($file), $url);
    }

    protected function urlToFileName($url)
    {
        return preg_replace('/[^a-zA-Z0-9]/', '-', $url);
    }
}
