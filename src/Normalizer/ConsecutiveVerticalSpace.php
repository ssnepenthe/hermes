<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class ConsecutiveVerticalSpace extends BaseNormalizer
{
    protected function doNormalize(string $value, Crawler $crawler) : string
    {
        return preg_replace('/\v+/u', PHP_EOL, $value);
    }
}
