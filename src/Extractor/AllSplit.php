<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;

class AllSplit extends BaseExtractor
{
    protected function doExtract(Crawler $crawler) : array
    {
        $values = $crawler->each(function (Crawler $subCrawler, int $index) {
            return explode(
                PHP_EOL,
                $this->getFirstValue($subCrawler->extract($this->attr))
            );
        });

        if (! empty($values)) {
            $values = call_user_func_array('array_merge', $values);
        }

        return $values;
    }
}
