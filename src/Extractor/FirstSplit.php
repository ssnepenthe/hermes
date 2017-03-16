<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;

class FirstSplit extends BaseExtractor
{
    protected function doExtract(Crawler $crawler) : array
    {
        return explode(
            PHP_EOL,
            $this->getFirstValue($crawler->first()->extract($this->attr))
        );
    }
}
