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
}
