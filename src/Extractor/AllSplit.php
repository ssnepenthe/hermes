<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;

class AllSplit extends BaseExtractor
{
    protected function doExtract(Crawler $crawler) : array
    {
        $values = $crawler->each(function (Crawler $subCrawler, int $index) {
            $subResult = $this->getFirstValue($subCrawler->extract($this->attr));

            $subResult = explode(PHP_EOL, $subResult);

            return array_filter(array_map('trim', $subResult));
        });

        $values = array_filter($values);

        if (! empty($values)) {
            $values = call_user_func_array('array_merge', $values);
        }

        return $values;
    }
}
