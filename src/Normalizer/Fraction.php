<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class Fraction extends BaseNormalizer
{
    public function normalize($value, Crawler $crawler) : array
    {
        $fraction = [
            '⅛', '⅜', '⅝', '⅞',
            '⅙', '⅚',
            '⅕', '⅖', '⅗', '⅘',
            '¼', '¾',
            '⅓', '⅔',
            '½',
        ];
        $number = [
            '1/8', '3/8', '5/8', '7/8',
            '1/6', '5/6',
            '1/5', '2/5', '3/5', '4/5',
            '1/4', '3/4',
            '1/3', '2/3',
            '1/2',
        ];

        return array_map(function ($val) use ($fraction, $number) {
            // Add a space if necessary, "1¼" -> "1 ¼"
            $val = preg_replace(
                sprintf('/(\d+)([%s])/u', implode('|', $fraction)),
                '$1 $2',
                $val
            );

            // Then replace the fraction.
            $val = str_replace($fraction, $number, $val);

            return trim($val);
        }, (array) $value);
    }
}
