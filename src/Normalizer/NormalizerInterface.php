<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

interface NormalizerInterface
{
    public function normalize($value, Crawler $crawler) : array;
}
