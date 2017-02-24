<?php

namespace SSNepenthe\Hermes\Matcher;

use Symfony\Component\DomCrawler\Crawler;

class DocumentMatcher extends BaseMatcher
{
    public static function getType() : string
    {
        return 'document';
    }

    protected function getHaystackFromCrawler(Crawler $crawler) : string
    {
        return $crawler->html();
    }
}
