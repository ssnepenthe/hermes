<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;

class All extends BaseExtractor
{
    protected function doExtract(Crawler $crawler) : array
    {
        return $crawler->each(function (Crawler $subCrawler, int $index) {
            $subResult = $subCrawler->extract($this->attr);

            return $this->getFirstValue($subResult);
        });
    }
}
