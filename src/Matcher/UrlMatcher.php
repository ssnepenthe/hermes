<?php

namespace SSNepenthe\Hermes\Matcher;

use Symfony\Component\DomCrawler\Crawler;

class UrlMatcher extends BaseMatcher
{
    public static function getType() : string
    {
        return 'url';
    }

    protected function getHaystackFromCrawler(Crawler $crawler) : string
    {
        return $crawler->getUri();
    }
}
