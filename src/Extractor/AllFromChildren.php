<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;
use function SSNepenthe\Hermes\result_return_value;

class AllFromChildren extends FromChildren
{
    public function extract(Crawler $crawler)
    {
        $result = $crawler->each(function (Crawler $c, int $i) {
            return $this->extractValueFromChildNodes($c->getNode(0));
        });

        return result_return_value($result);
    }
}
