<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;

abstract class BaseExtractor implements ExtractorInterface
{
    protected $attr;

    public function __construct(string $attr)
    {
        $this->attr = $attr;
    }

    public function extract(Crawler $crawler) : array
    {
        // Filtering like this drops the string "0"... Might be a problem eventually.
        return array_filter(array_map('trim', $this->doExtract($crawler)));
    }

    protected function getFirstValue(array $values, $default = '')
    {
        if (empty($values)) {
            return $default;
        }

        return reset($values);
    }

    abstract protected function doExtract(Crawler $crawler) : array;
}
