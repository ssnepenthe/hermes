<?php

namespace SSNepenthe\Hermes\Scraper;

use Symfony\Component\DomCrawler\Crawler;

class ScraperResolver implements ScraperResolverInterface
{
    /**
     * Order is important... Resolve() priority is based on match type.
     *
     * @todo  Might want to push selector to last priority?
     */
    protected $scrapers = [
        'url' => [],
        'host' => [],
        'title' => [],
        'selector' => [],
        'document' => [],
    ];

    public function __construct(array $scrapers = [])
    {
        foreach ($scrapers as $scraper) {
            $this->addScraper($scraper);
        }
    }

    public function addScraper(ScraperInterface $scraper)
    {
        // @todo getMatcher on interface?
        $type = $scraper->getMatcher()->getType();

        if (! isset($this->scrapers[$type])) {
            $this->scrapers[$type] = [];
        }

        $this->scrapers[$type][] = $scraper;
    }

    public function getScrapers(string $type = null) : array
    {
        if (! is_null($type)) {
            return $this->scrapers[$type] ?? [];
        }

        return $this->scrapers;
    }

    public function resolve(Crawler $crawler)
    {
        foreach ($this->scrapers as $type => $scrapers) {
            foreach ($scrapers as $scraper) {
                if ($scraper->matches($crawler)) {
                    return $scraper;
                }
            }
        }

        // @todo Throw exception?
        return false;
    }
}
