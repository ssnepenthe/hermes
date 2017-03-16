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
        // Very likely need to revisit this eventually.
        // DateInterval::createFromDateString() doesn't recognize "hr" abbreviation.
        $newValue = str_replace('hr', 'hour', $value);

        try {
            // First try ISO8601.
            $interval = new DateInterval($newValue);
        } catch (Exception $e) {
            // Fall back to relative time string.
            $interval = DateInterval::createFromDateString($newValue);
        }

        // If we end up with an empty interval, return original value.
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
