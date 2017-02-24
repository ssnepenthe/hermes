<?php

namespace SSNepenthe\Hermes\Matcher;

use Symfony\Component\DomCrawler\Crawler;

class NullMatcher implements MatcherInterface
{
    public static function getType() : string
    {
        return 'universal';
    }

    public function matches(Crawler $crawler) : bool
    {
        return true;
    }
}
