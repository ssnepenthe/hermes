<?php

namespace SSNepenthe\Hermes\Matcher;

use Symfony\Component\DomCrawler\Crawler;

class SelectorMatcher implements MatcherInterface
{
    protected $pattern;

    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    public static function getType() : string
    {
        return 'selector';
    }

    public function matches(Crawler $crawler) : bool
    {
        return (bool) $crawler->filter($this->pattern)->count();
    }
}
