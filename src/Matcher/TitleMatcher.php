<?php

namespace SSNepenthe\Hermes\Matcher;

use Symfony\Component\DomCrawler\Crawler;

class TitleMatcher extends BaseMatcher
{
    public static function getType() : string
    {
        return 'title';
    }

    protected function getHaystackFromCrawler(Crawler $crawler) : string
    {
        return $crawler->filter('title')->first()->text();
    }
}
