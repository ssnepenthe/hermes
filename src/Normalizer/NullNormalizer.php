<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class NullNormalizer extends BaseNormalizer
{
    public function normalize($value, Crawler $crawler) : array
    {
        return (array) $value;
    }
}
