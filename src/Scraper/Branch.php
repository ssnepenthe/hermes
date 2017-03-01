<?php

namespace SSNepenthe\Hermes\Scraper;

use SSNepenthe\Hermes\Matcher\MatcherInterface;

class Branch extends Root
{
    public function __construct(
        string $name,
        MatcherInterface $matcher = null,
        string $selector = null,
        array $children = []
    ) {
        parent::__construct($name, $matcher, $selector, $children);
    }
}
