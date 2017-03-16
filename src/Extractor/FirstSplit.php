<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;

class FirstSplit extends BaseExtractor
{
    protected function doExtract(Crawler $crawler) : array
    {
        $values = $this->getFirstValue($crawler->first()->extract($this->attr));

        $values = explode(PHP_EOL, $values);

        return array_map('trim', $values);
    }
}
