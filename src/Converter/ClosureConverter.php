<?php

namespace SSNepenthe\Hermes\Converter;

use Closure;
use Symfony\Component\DomCrawler\Crawler;

class ClosureConverter implements ConverterInterface
{
    protected $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function convert(array $values, Crawler $crawler) : array
    {
        return (array) call_user_func($this->closure, $values, $crawler);
    }
}
