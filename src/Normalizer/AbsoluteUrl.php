<?php

namespace SSNepenthe\Hermes\Normalizer;

use Symfony\Component\DomCrawler\Crawler;

class AbsoluteUrl implements NormalizerInterface
{
    /**
     * Far from perfect...
     *
     * @link http://nadeausoftware.com/node/79
     */
    public function normalize(array $values, Crawler $crawler) : array
    {
        $url = parse_url($crawler->getUri());

        return array_map(function ($value) use ($url) {
            $parsed = parse_url($value);

            // We probably shouldn't be here ($value is not URL).
            if (empty($parsed['path']) || '/' !== $parsed['path'][0]) {
                return $value;
            }

            if (! isset($parsed['host']) && isset($url['host'])) {
                $value = $url['host'] . '/' . ltrim($value, '/');
            }

            // @todo Scheme shouldn't be set if host was not set - need to verify.
            if (! isset($parsed['scheme']) && isset($url['scheme'])) {
                $value = $url['scheme'] . '://' . ltrim($value, ':/');
            }

            return $value;
        }, $values);
    }
}
