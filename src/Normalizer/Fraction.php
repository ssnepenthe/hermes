<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class Fraction extends BaseNormalizer
{
    protected $fractions = [
        '⅛', '⅜', '⅝', '⅞',
        '⅙', '⅚',
        '⅕', '⅖', '⅗', '⅘',
        '¼', '¾',
        '⅓', '⅔',
        '½',
    ];
    protected $numbers = [
        '1/8', '3/8', '5/8', '7/8',
        '1/6', '5/6',
        '1/5', '2/5', '3/5', '4/5',
        '1/4', '3/4',
        '1/3', '2/3',
        '1/2',
    ];

    protected function doNormalize(string $value, Crawler $crawler) : string
    {
        // Add a space if necessary, "1¼" -> "1 ¼"
        $value = preg_replace(
            sprintf('/(\d+)([%s])/u', implode('|', $this->fractions)),
            '$1 $2',
            $value
        );

        // Then replace the fraction.
        $value = str_replace($this->fractions, $this->numbers, $value);

        return $value;
    }
}
