<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;

interface ExtractorInterface
{
    public function extract(Crawler $crawler) : array;
}
