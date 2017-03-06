<?php

namespace SSNepenthe\Hermes\Matcher;

use Closure;
use Symfony\Component\DomCrawler\Crawler;

class ClosureMatcher implements MatcherInterface
{
    protected $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public static function getType() : string
    {
        return 'closure';
    }

    public function matches(Crawler $crawler) : bool
    {
        return (bool) call_user_func($this->closure, $crawler);
    }
}
