<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class VerticalSpace extends BaseNormalizer
{
    protected function doNormalize(string $value, Crawler $crawler) : string
    {
        return preg_replace('/(\r\n|\v)/u', PHP_EOL, $value);
    }
}
