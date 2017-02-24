<?php

namespace SSNepenthe\Hermes\Converter;

use Exception;
use DateInterval;
use Symfony\Component\DomCrawler\Crawler;

class ToInterval extends BaseConverter
{
    public function convert($value, Crawler $crawler = null) : array
    {
        return $this->filterAndReIndex(array_map(function ($v) {
            try {
                $interval = new DateInterval($v);
            } catch (Exception $e) {
                $interval = DateInterval::createFromDateString($v);
            }

            // Did DateInterval::createFromDateString create an empty interval?
            if (! $interval->y && ! $interval->m && ! $interval->d &&
                ! $interval->h && ! $interval->i && ! $interval->s
            ) {
                $interval = $v;
            }

            return $interval;
        }, (array) $value));
    }
}
