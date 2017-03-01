<?php

namespace SSNepenthe\Hermes\Scraper;

use Symfony\Component\DomCrawler\Crawler;

interface ScraperInterface
{
    public function getName() : string;
    public function matches(Crawler $crawler) : bool;
    public function scrape(Crawler $crawler);
}
