<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;
use function SSNepenthe\Hermes\result_return_value;

class AllFromChildren extends FromChildren
{
    public function extract(Crawler $crawler)
    {
        $result = $crawler->each(function (Crawler $subCrawler, int $index) {
            return $this->extractValueFromChildNodes($subCrawler->getNode(0));
        });

        return result_return_value($result);
    }
}
