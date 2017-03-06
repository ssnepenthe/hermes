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

    public function convert($value, Crawler $crawler) : array
    {
        $newValue = $value;

        foreach ($this->stack as $converter) {
            $newValue = $converter->convert($newValue, $crawler);
        }

        return $newValue;
    }

    public function getConverters() : array
    {
        return $this->stack;
    }
}
