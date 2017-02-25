<?php

namespace SSNepenthe\Hermes\Scraper;

use SSNepenthe\Hermes\Scraper\Scraper;
use SSNepenthe\Hermes\Matcher\UrlMatcher;
use Symfony\Component\Config\FileLocator;
use SSNepenthe\Hermes\Matcher\HostMatcher;
use SSNepenthe\Hermes\Matcher\NullMatcher;
use SSNepenthe\Hermes\Loader\PhpFileLoader;
use SSNepenthe\Hermes\Matcher\TitleMatcher;
use SSNepenthe\Hermes\Loader\JsonFileLoader;
use SSNepenthe\Hermes\Loader\YamlFileLoader;
use SSNepenthe\Hermes\Matcher\DocumentMatcher;
use SSNepenthe\Hermes\Matcher\SelectorMatcher;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use SSNepenthe\Hermes\Definition\ScraperConfiguration;

class ScraperFactory
{
    public static function fromConfigArray(array $config)
    {
        $converter = static::makeConverter(
                empty($config['converters']) ? [] : $config['converters']
            );
        $matcher = static::makeMatcher(
            $config['matchPattern'] ?? null,
            $config['matchType'] ?? null
        );
        $normalizer = static::makeNormalizer(
            empty($config['normalizers']) ? [] : $config['normalizers']
        );
        $name = $config['name'] ?? 'root';
        $attr = $config['attr'] ?? '_text';
        $selector = $config['selector'] ?? '';
        $children = empty($config['schema'])
            ? []
            : static::makeChildScrapers($config['schema']);

        return new Scraper(
            $converter,
            $matcher,
            $normalizer,
            $name,
            $attr,
            $selector,
            $children
        );
    }

    public static function fromConfigFile(string $file)
    {
        return static::fromConfigFiles([$file]);
    }

    public static function fromConfigFiles(array $files)
    {
        $files = array_map(function (string $file) : string {
            return realpath($file);
        }, (array) $files);

        $locator = new FileLocator;
        $loader = new DelegatingLoader(
            new LoaderResolver([
                new PhpFileLoader($locator),
                new YamlFileLoader($locator),
                new JsonFileLoader($locator),
            ])
        );
        $scrapers = [];
        $processor = new Processor;
        $configuration = new ScraperConfiguration;

        foreach ($files as $file) {
            $processed = $processor->processConfiguration(
                $configuration,
                $loader->load($file)
            );

            $scrapers[] = static::fromConfigArray($processed);
        }

        return new DelegatingScraper(
            new ScraperResolver($scrapers)
        );
    }

    public static function fromGlob(string $pattern)
    {
        return static::fromConfigFiles(glob($pattern, GLOB_BRACE));
    }

    protected static function makeChildScrapers(array $schema)
    {
        $scrapers = [];

        foreach ($schema as $name => $child) {
            $converter = static::makeConverter(
                empty($child['converters']) ? [] : $child['converters']
            );
            $matcher = static::makeMatcher(
                $child['matchPattern'] ?? null,
                $child['matchType'] ?? null
            );
            $normalizer = static::makeNormalizer(
                empty($child['normalizers']) ? [] : $child['normalizers']
            );
            $attr = $child['attr'] ?? '_text';
            $selector = $child['selector'] ?? '';
            $children = empty($child['schema'])
                ? []
                : static::makeChildScrapers($child['schema']);

            $scrapers[] = new Scraper(
                $converter,
                $matcher,
                $normalizer,
                $name,
                $attr,
                $selector,
                $children
            );
        }

        return $scrapers;
    }

    protected static function makeMatcher(
        string $pattern = null,
        string $type = null
    ) {
        if (is_null($pattern) || is_null($type)) {
            return new NullMatcher;
        }

        $map = [
            DocumentMatcher::getType() => DocumentMatcher::class,
            HostMatcher::getType()     => HostMatcher::class,
            SelectorMatcher::getType() => SelectorMatcher::class,
            TitleMatcher::getType()    => TitleMatcher::class,
            UrlMatcher::getType()      => UrlMatcher::class,
        ];

        if (isset($map[$type])) {
            return new $map[$type]($pattern);
        }

        // @todo
        throw new NoMatchingMatcherException;
    }

    protected static function makeConverter(array $converters)
    {
        if (empty($converters)) {
            return [function ($value) { return $value; }];
        }

        $stack = [];

        foreach ($converters as $converter) {
            $stack[] = $converter;
        }

        return $stack;
    }

    protected static function makeNormalizer(array $normalizers)
    {
        if (empty($normalizers)) {
            return [function ($value) { return $value; }];
        }

        $stack = [];

        foreach ($normalizers as $normalizer) {
            $stack[] = $normalizer;
        }

        return $stack;
    }
}
