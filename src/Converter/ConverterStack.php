<?php

namespace SSNepenthe\Hermes\Converter;

use Symfony\Component\DomCrawler\Crawler;

class ConverterStack implements ConverterInterface
{
    protected $stack = [];

    public function __construct(array $converters = [])
    {
        foreach ($converters as $converter) {
            $this->addConverter($converter);
        }
    }

    public function addConverter(ConverterInterface $converter)
    {
        $this->stack[] = $converter;
    }

    public function convert(array $values, Crawler $crawler) : array
    {
        $newValues = $values;

        foreach ($this->stack as $converter) {
            $newValues = $converter->convert($newValues, $crawler);
        }

        return $newValues;
    }

    public function getConverters() : array
    {
        return $this->stack;
    }
}
