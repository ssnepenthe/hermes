<?php

namespace SSNepenthe\Hermes\Converter;

use Symfony\Component\DomCrawler\Crawler;

class NullConverter extends BaseConverter
{
    public function convert($value, Crawler $crawler = null) : array
    {
        return (array) $value;
    }
}
