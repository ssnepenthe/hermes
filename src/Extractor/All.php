<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;
use function SSNepenthe\Hermes\result_return_value;

class All extends BaseExtractor
{
    public function extract(Crawler $crawler)
    {
        $result = $crawler->each(function (Crawler $subCrawler, int $index) {
            $subResult = $subCrawler->extract($this->attr);

            return result_return_value($subResult);
        });

        return result_return_value($result);
    }
}
