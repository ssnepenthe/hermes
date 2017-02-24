<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class VerticalSpace extends BaseNormalizer
{
    public function normalize($value, Crawler $crawler) : array
    {
        return $this->filterAndReIndex(array_map(function ($val) {
            return trim(preg_replace('/(\r\n|\v)/u', PHP_EOL, $val));
        }, (array) $value));
    }
}
