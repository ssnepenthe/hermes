<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;

class First extends BaseExtractor
{
    protected function doExtract(Crawler $crawler) : array
    {
        return $crawler->first()->extract($this->attr);
    }
}
