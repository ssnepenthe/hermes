<?php

namespace SSNepenthe\Hermes\Extractor;

use Closure;
use Symfony\Component\DomCrawler\Crawler;

class ClosureExtractor implements ExtractorInterface
{
    protected $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function extract(Crawler $crawler) : array
    {
        return (array) call_user_func($this->closure, $crawler);
    }
}
