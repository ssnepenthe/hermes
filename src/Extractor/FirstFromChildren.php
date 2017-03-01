<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;
use function SSNepenthe\Hermes\result_return_value;

class FirstFromChildren extends FromChildren
{
    public function extract(Crawler $crawler)
    {
        $result = $this->extractValueFromChildNodes($crawler->getNode(0));

        return result_return_value($result);
    }
}
