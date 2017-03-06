<?php

namespace SSNepenthe\Hermes\Normalizer;

use Closure;
use Symfony\Component\DomCrawler\Crawler;

class ClosureNormalizer implements NormalizerInterface
{
    protected $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function normalize($value, Crawler $crawler) : array
    {
        return (array) call_user_func($this->closure, $value, $crawler);
    }
}
