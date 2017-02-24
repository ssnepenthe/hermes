<?php

namespace SSNepenthe\Hermes\Scraper;

use Symfony\Component\DomCrawler\Crawler;

interface ScraperResolverInterface
{
    public function resolve(Crawler $crawler);
}
