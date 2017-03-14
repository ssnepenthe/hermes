<?php

namespace SSNepenthe\Hermes\Scraper;

use SSNepenthe\Hermes\Extractor\All;
use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Matcher\NullMatcher;
use SSNepenthe\Hermes\Converter\NullConverter;
use SSNepenthe\Hermes\Matcher\MatcherInterface;
use SSNepenthe\Hermes\Normalizer\NullNormalizer;
use SSNepenthe\Hermes\Converter\ConverterInterface;
use SSNepenthe\Hermes\Extractor\ExtractorInterface;
use function SSNepenthe\Hermes\result_return_value;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;

class Leaf implements ScraperInterface
{
    protected $converter;
    protected $extractor;
    protected $matcher;
    protected $name;
    protected $normalizer;
    protected $selector;
    protected $type;

    public function __construct(
        string $name,
        string $type = 'singular',
        MatcherInterface $matcher = null,
        ExtractorInterface $extractor = null,
        NormalizerInterface $normalizer = null,
        ConverterInterface $converter = null,
        string $selector = null
    ) {
        $this->name = $name;
        $this->type = 'singular' === $type ? 'singular' : 'plural';
        $this->matcher = $matcher ?: new NullMatcher;
        $this->extractor = $extractor ?: new All('_text');
        $this->normalizer = $normalizer ?: new NullNormalizer;
        $this->converter = $converter ?: new NullConverter;
        $this->selector = $selector;
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

        $result = $this->extractor->extract($crawler);
        $result = $this->normalizer->normalize($result, $crawler);
        $result = $this->converter->convert($result, $crawler);

        if ('singular' === $this->type) {
            return empty($result) ? '' : reset($result);
        }

        return $result;

        return result_return_value($result);
    }
}
