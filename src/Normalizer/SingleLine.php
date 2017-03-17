<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class SingleLine extends BaseNormalizer
{
    protected function doNormalize(string $value, Crawler $crawler) : string
    {
        // Split on newline.
        $value = explode(PHP_EOL, $value);

        // Trim whitespace.
        $value = array_map('trim', $value);

        // Remove empty entries.
        $value = array_filter($value);

        // Put it back together.
        return implode(' ', $value);
    }
}
