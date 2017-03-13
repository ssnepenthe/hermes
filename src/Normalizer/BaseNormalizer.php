<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

abstract class BaseNormalizer implements NormalizerInterface
{
    public function normalize(array $values, Crawler $crawler) : array
    {
        $values = array_map(function ($value) use ($crawler) {
            return trim($this->doNormalize($value, $crawler));
        }, $values);

        return array_filter($values);
    }

    abstract protected function doNormalize(string $value, Crawler $crawler) : string;
}
