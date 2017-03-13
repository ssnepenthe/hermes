<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class HorizontalSpace extends BaseNormalizer
{
    protected function doNormalize(string $value, Crawler $crawler) : string
    {
        // @todo '&nbsp;' ?
        return preg_replace('/\h/u', ' ', $value);
    }
}
