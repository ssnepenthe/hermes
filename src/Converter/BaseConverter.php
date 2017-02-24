<?php

namespace SSNepenthe\Hermes\Converter;

use Symfony\Component\DomCrawler\Crawler;

abstract class BaseConverter implements ConverterInterface
{
    public function __invoke($value, Crawler $crawler = null)
    {
        return $this->convert($value, $crawler);
    }

    protected function filterAndReIndex(array $values)
    {
        return array_values(array_filter($values));
    }
}
