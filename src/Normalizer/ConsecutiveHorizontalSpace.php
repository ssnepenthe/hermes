<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class ConsecutiveHorizontalSpace extends BaseNormalizer
{
    protected function doNormalize(string $value, Crawler $crawler) : string
    {
        return preg_replace('/\h{2,}/u', ' ', $value);
    }
}
