<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class AbsoluteUrl extends BaseNormalizer
{
    /**
     * Far from perfect...
     *
     * @link http://nadeausoftware.com/node/79
     */
    public function normalize($value, Crawler $crawler) : array
    {
        $url = parse_url($crawler->getUri());

        return $this->filterAndReIndex(array_map(function ($val) use ($url) {
            $parsed = parse_url($val);

            // We probably shouldn't be here ($val is not URL).
            if (empty($parsed['path']) || '/' !== $parsed['path'][0]) {
                return $val;
            }

            if (! isset($parsed['host']) && isset($url['host'])) {
                $val = $url['host'] . '/' . ltrim($val, '/');
            }

            // @todo Scheme shouldn't be set if host was not set - need to verify.
            if (! isset($parsed['scheme']) && isset($url['scheme'])) {
                $val = $url['scheme'] . '://' . ltrim($val, ':/');
            }

            return trim($val);
        }, (array) $value));
    }
}

