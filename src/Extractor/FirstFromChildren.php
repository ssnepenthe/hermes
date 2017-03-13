<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;

class FirstFromChildren extends FromChildren
{
    protected function doExtract(Crawler $crawler) : array
    {
        return (array) $this->extractValueFromChildNodes($crawler->getNode(0));
    }
}
