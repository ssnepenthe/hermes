<?php

namespace SSNepenthe\Hermes\Converter;

use DateTime;
use Exception;
use DateInterval;
use Symfony\Component\DomCrawler\Crawler;

class ToInterval extends BaseConverter
{
    protected function doConvert(string $value, Crawler $crawler)
    {
        try {
            // First try ISO8601.
            $interval = new DateInterval($value);
        } catch (Exception $e) {
            // Fall back to relative time string.
            $interval = DateInterval::createFromDateString($value);
        }

        // If $value is not a valid interval string we will have an empty interval.
        if ($this->isEmptyInterval($interval)) {
            return $value;
        }

        return $this->recalculateCarryOver($interval);
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
