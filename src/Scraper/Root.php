<?php

namespace SSNepenthe\Hermes\Scraper;

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Matcher\NullMatcher;
use SSNepenthe\Hermes\Matcher\MatcherInterface;
use function SSNepenthe\Hermes\result_return_value;

class Root implements ScraperInterface
{
    protected $children = [];
    protected $name;
    protected $matcher;
    protected $selector;

    public function __construct(
        string $name = 'root',
        MatcherInterface $matcher = null,
        string $selector = null,
        array $children = []
    ) {
        $this->name = $name;
        $this->matcher = $matcher ?: new NullMatcher;
        $this->selector = $selector;

        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    public function addChild(ScraperInterface $child)
    {
        $this->children[] = $child;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function matches(Crawler $crawler) : bool
    {
        return $this->matcher->matches($crawler);
    }

    public function scrape(Crawler $crawler)
    {
        if (! is_null($this->selector)) {
            $crawler = $crawler->filter($this->selector);
        }

        $children = $this->children;

        $result = $crawler->each(function (Crawler $c, int $i) use ($children) {
            $r = [];

            foreach ($children as $child) {
                $r[$child->getName()] = $child->scrape($c);
            }

            return $r;
        });

        return result_return_value($result, []);
    }
}
