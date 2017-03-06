<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class NullNormalizer implements NormalizerInterface
{
    public function normalize($value, Crawler $crawler) : array
    {
        return (array) $value;
    }
}
