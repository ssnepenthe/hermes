<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class ConsecutiveHorizontalSpace extends BaseNormalizer
{
    public function normalize($value, Crawler $crawler) : array
    {
        return $this->filterAndReIndex(array_map(function ($val) {
            return trim(preg_replace('/\h{2,}/u', ' ', $val));
        }, (array) $value));
    }
}

