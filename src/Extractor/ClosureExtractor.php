<?php

namespace SSNepenthe\Hermes\Extractor;

use Closure;
use Symfony\Component\DomCrawler\Crawler;
use function SSNepenthe\Hermes\result_return_value;

class ClosureExtractor implements ExtractorInterface
{
    protected $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function extract(Crawler $crawler)
    {
        return result_return_value(call_user_func($this->closure, $crawler));
    }
}
