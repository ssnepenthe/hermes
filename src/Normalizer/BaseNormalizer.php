<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

abstract class BaseNormalizer implements NormalizerInterface
{
    public function normalize(array $values, Crawler $crawler) : array
    {
        return array_map(function ($value) use ($crawler) {
            return $this->doNormalize($value, $crawler);
        }, $values);
    }

    abstract protected function doNormalize(string $value, Crawler $crawler) : string;
}
