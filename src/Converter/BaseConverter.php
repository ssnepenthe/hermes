<?php

namespace SSNepenthe\Hermes\Converter;

use Symfony\Component\DomCrawler\Crawler;

abstract class BaseConverter implements ConverterInterface
{
    public function convert(array $values, Crawler $crawler) : array
    {
        return array_map(function ($value) use ($crawler) {
            return $this->doConvert($value, $crawler);
        }, $values);
    }

    abstract protected function doConvert(string $value, Crawler $crawler);
}
