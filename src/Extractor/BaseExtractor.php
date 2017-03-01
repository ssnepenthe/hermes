<?php

namespace SSNepenthe\Hermes\Extractor;

use Symfony\Component\DomCrawler\Crawler;
use function SSNepenthe\Hermes\result_return_value;

abstract class BaseExtractor implements ExtractorInterface
{
    protected $attr;

    public function __construct(string $attr)
    {
        $this->attr = $attr;
    }

    public function __invoke(Crawler $crawler)
    {
        return $this->extract($crawler);
    }
}
