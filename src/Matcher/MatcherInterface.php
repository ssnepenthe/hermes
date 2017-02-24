<?php

namespace SSNepenthe\Hermes\Matcher;

use Symfony\Component\DomCrawler\Crawler;

interface MatcherInterface
{
    public static function getType() : string;
    public function matches(Crawler $crawler) : bool;
}
