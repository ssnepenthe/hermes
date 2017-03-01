<?php

namespace SSNepenthe\Hermes\Scraper;

use Symfony\Component\DomCrawler\Crawler;

class DelegatingScraper implements ScraperInterface
{
    protected $resolver;

    public function __construct(ScraperResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    public function getName() : string
    {
        return 'delegating-scraper';
    }

    public function getResolver() : ScraperResolverInterface
    {
        return $this->resolver;
    }

    public function matches(Crawler $crawler) : bool
    {
        return false !== $this->resolver->resolve($crawler);
    }

    public function scrape(Crawler $crawler) : array
    {
        if (false === $scraper = $this->resolver->resolve($crawler)) {
            // @todo
            throw new \RuntimeException;
        }

        return $scraper->scrape($crawler);
    }
}
