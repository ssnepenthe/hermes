<?php

namespace SSNepenthe\Hermes\Matcher;

use Symfony\Component\DomCrawler\Crawler;

abstract class BaseMatcher implements MatcherInterface
{
    protected $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function matches(Crawler $crawler) : bool
    {
        $haystack = $this->getHaystackFromCrawler($crawler);

        if ($this->patternIsRegex()) {
            return (bool) preg_match($this->pattern, $haystack);
        }

        return false !== strpos($haystack, $this->pattern);
    }

    protected function patternIsRegex() : bool
    {
        return '/' === $this->pattern[0] && '/' === substr($this->pattern, -1);
    }

    abstract protected function getHaystackFromCrawler(Crawler $crawler) : string;
}
