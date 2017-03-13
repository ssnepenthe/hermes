<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class NullNormalizer implements NormalizerInterface
{
    public function normalize(array $values, Crawler $crawler) : array
    {
        return $values;
    }
}
