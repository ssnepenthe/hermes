<?php

namespace SSNepenthe\Hermes\Converter;

use Symfony\Component\DomCrawler\Crawler;

interface ConverterInterface
{
    public function convert(array $values, Crawler $crawler) : array;
}
