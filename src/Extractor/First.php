<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;
use function SSNepenthe\Hermes\result_return_value;

class First extends BaseExtractor
{
    public function extract(Crawler $crawler)
    {
        return result_return_value($crawler->first()->extract($this->attr));
    }
}
