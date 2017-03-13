<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class NormalizerStack implements NormalizerInterface
{
    protected $stack = [];

    public function __construct(array $normalizers = [])
    {
        foreach ($normalizers as $normalizer) {
            $this->addNormalizer($normalizer);
        }
    }

    public function addNormalizer(NormalizerInterface $normalizer)
    {
        $this->stack[] = $normalizer;
    }

    public function getNormalizers() : array
    {
        return $this->stack;
    }

    public function normalize(array $values, Crawler $crawler) : array
    {
        $newValues = $values;

        foreach ($this->stack as $normalizer) {
            $newValues = $normalizer->normalize($newValues, $crawler);
        }

        return $newValues;
    }
}
