<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;

class AllFromChildren extends FromChildren
{
    protected function doExtract(Crawler $crawler) : array
    {
        return $crawler->each(function (Crawler $subCrawler, int $index) {
            return $this->extractValueFromChildNodes($subCrawler->getNode(0));
        });
    }
}
