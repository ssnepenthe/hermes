<?php

namespace SSNepenthe\Hermes\Converter;

use DateTime;
use Exception;
use DateInterval;
use Symfony\Component\DomCrawler\Crawler;

class ToInterval implements ConverterInterface
{
    public function convert($value, Crawler $crawler) : array
    {
        return array_map(function ($val) {
            try {
                // First try ISO8601.
                $interval = new DateInterval($val);
            } catch (Exception $e) {
                // Fall back to relative time string.
                $interval = DateInterval::createFromDateString($val);
            }

            // If $val is not a valid interval string we will have an empty interval.
            if ($this->isEmptyInterval($interval)) {
                return $val;
            }

            return $this->recalculateCarryOver($interval);
        }, (array) $value);
    }

    protected function isEmptyInterval(DateInterval $interval) : bool
    {
        return ! $interval->y
            && ! $interval->m
            && ! $interval->d
            && ! $interval->h
            && ! $interval->i
            && ! $interval->s;
    }

    protected function recalculateCarryOver(DateInterval $interval) : DateInterval
    {
        $dt1 = new DateTime;
        $dt2 = clone $dt1;

        $dt2->add($interval);

        return $dt1->diff($dt2);
    }
}
