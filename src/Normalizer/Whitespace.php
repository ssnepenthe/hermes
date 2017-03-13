<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class Whitespace extends BaseNormalizer
{
    protected function doNormalize(string $value, Crawler $crawler) : string
    {
        // Normalize whitespace characters.
        $value = preg_replace(['/\h/u', '/(\r\n|\v)/u'], [' ', PHP_EOL], $value);

        // Split on EOL.
        $value = explode(PHP_EOL, $value);

        // Trim each line.
        $value = array_map('trim', $value);

        // Remove empty lines.
        $value = array_filter($value);

        // Put it all back together.
        $value = implode(PHP_EOL, $value);

        return $value;
    }
}
