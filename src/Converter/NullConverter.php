<?php

namespace SSNepenthe\Hermes\Converter;

use Symfony\Component\DomCrawler\Crawler;

class NullConverter implements ConverterInterface
{
    public function convert(array $values, Crawler $crawler) : array
    {
        return $values;
    }
}
