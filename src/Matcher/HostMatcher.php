<?php

namespace SSNepenthe\Hermes\Matcher;

use Symfony\Component\DomCrawler\Crawler;

class HostMatcher extends BaseMatcher
{
    public static function getType() : string
    {
        return 'host';
    }

    protected function getHaystackFromCrawler(Crawler $crawler) : string
    {
        return parse_url($crawler->getUri(), PHP_URL_HOST);
    }
}
