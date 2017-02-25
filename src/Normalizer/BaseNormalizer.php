<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

abstract class BaseNormalizer implements NormalizerInterface
{
    public function __invoke($value, Crawler $crawler)
    {
        return $this->normalize($value, $crawler);
    }
}
