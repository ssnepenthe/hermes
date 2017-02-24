<?php

namespace SSNepenthe\Hermes\Matcher;

use Symfony\Component\DomCrawler\Crawler;

class SelectorMatcher implements MatcherInterface
{
    public static function getType() : string
    {
        return 'selector';
    }

    public function matches(Crawler $crawler) : bool
    {
        return (bool) $crawler->filter($this->pattern)->count();
    }
}
