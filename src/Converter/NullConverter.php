<?php

namespace SSNepenthe\Hermes\Converter;

use Symfony\Component\DomCrawler\Crawler;

class NullConverter implements ConverterInterface
{
    public function convert($value, Crawler $crawler) : array
    {
        return (array) $value;
    }
}
