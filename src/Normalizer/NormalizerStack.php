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

    public function normalize($value, Crawler $crawler) : array
    {
        $newValue = $value;

        foreach ($this->stack as $normalizer) {
            $newValue = $normalizer->normalize($newValue, $crawler);
        }

        return $newValue;
    }
}
