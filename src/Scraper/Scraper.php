<?php

namespace SSNepenthe\Hermes\Scraper;

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Matcher\MatcherInterface;

class Scraper implements ScraperInterface
{
    protected $name;
    protected $converters;
    protected $matcher;
    protected $normalizers;
    protected $attr;
    protected $selector;
    protected $children = [];

    public function __construct(
        array $converters,
        MatcherInterface $matcher,
        array $normalizers,
        string $name,
        string $attr = null,
        string $selector = null,
        array $children = []
    ) {
        $this->name = $name;
        $this->matcher = $matcher;
        $this->attr = $attr;
        $this->selector = $selector;

        foreach ($converters as $converter) {
            $this->addConverter($converter);
        }

        foreach ($normalizers as $normalizer) {
            $this->addNormalizer($normalizer);
        }

        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    public function addChild(Scraper $child)
    {
        // @todo Set parent reference on child?
        $this->children[] = $child;
    }

    public function addConverter(callable $converter)
    {
        $this->converters[] = $converter;
    }

    public function addNormalizer(callable $normalizer)
    {
        $this->normalizers[] = $normalizer;
    }

    public function getMatcher() : MatcherInterface
    {
        return $this->matcher;
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
        if (! $this->matches($crawler)) {
            return [];
        }

        if ($this->selector) {
            $crawler = $crawler->filter($this->selector);
        }

        $result = $crawler->each(function (Crawler $c, int $i) {
            $scrapeMethod = empty($this->children) ? 'doScrape' : 'doSubScrape';

            return $this->{$scrapeMethod}($c);
        });

        $result = $this->filterAndReIndex($result);

        if (1 === count($result)) {
            $result = current($result);
        }

        return $result;
    }

    protected function convert($value, Crawler $crawler)
    {
        $newValue = $value;

        foreach ($this->converters as $converter) {
            $newValue = $converter($value, $crawler);
        }

        return $newValue;
    }

    /**
     * @todo Pre* and Post* events?
     */
    protected function doScrape(Crawler $crawler)
    {
        if (is_null($this->attr)) {
            return [];
        }

        // @todo Extractor callable?
        $data = $crawler->extract($this->attr);

        $data = $this->normalize($data, $crawler);
        $data = $this->convert($data, $crawler);

        $data = $this->filterAndReIndex($data);

        if (1 === count($data) && ! is_array($first = current($data))) {
            $data = $first;
        }

        return $data;
    }

    protected function doSubScrape(Crawler $crawler)
    {
        $result = [];

        foreach ($this->children as $child) {
            $result[$child->getName()] = $child->scrape($crawler);
        }

        return $result;
    }

    protected function filterAndReIndex(array $values) : array
    {
        return array_vales(array_filter($values));
    }

    protected function normalize($value, Crawler $crawler)
    {
        $newValue = $value;

        foreach ($this->normalizers as $normalizer) {
            $newValue = $normalizer($value, $crawler);
        }

        return $newValue;
    }
}
