<?php

namespace SSNepenthe\Hermes\Scraper;

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Matcher\MatcherInterface;

interface ScraperInterface
{
    public function matches(Crawler $crawler) : bool;
    public function scrape(Crawler $crawler);
}
