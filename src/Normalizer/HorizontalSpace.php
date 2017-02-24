<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class HorizontalSpace extends BaseNormalizer
{
    public function normalize($value, Crawler $crawler) : array
    {
        return $this->filterAndReIndex(array_map(function ($val) {
            // @todo '&nbsp;' ?
            return trim(preg_replace('/\h/u', ' ', $val));
        }, (array) $value));
    }
}
