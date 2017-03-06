<?php

namespace SSNepenthe\Hermes\Scraper;

use Symfony\Component\Config\FileLocator;
use SSNepenthe\Hermes\Loader\PhpFileLoader;
use SSNepenthe\Hermes\Loader\JsonFileLoader;
use SSNepenthe\Hermes\Loader\YamlFileLoader;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use SSNepenthe\Hermes\Definition\ScraperConfiguration;

class ScraperFactory
{
    public static function fromConfigFile(string $file, int $depth = 5) : Root
    {
        if (! $file = realpath($file)) {
            throw new \InvalidArgumentException;
        }

        $locator = new FileLocator;
        $loader = new DelegatingLoader(
            new LoaderResolver([
                new PhpFileLoader($locator),
                new YamlFileLoader($locator),
                new JsonFileLoader($locator),
            ])
        );
        $configuration = new ScraperConfiguration($depth);
        $processor = new Processor;

        $processed = $processor->processConfiguration(
            $configuration,
            $loader->load($file)
        );

        $root = new Root('root', $processed['matcher']);

        foreach ($processed['schema'] as $name => $child) {
            static::addChildToScraper($name, $child, $root);
        }

        return $root;
    }

    protected static function addChildToScraper(
        string $name,
        array $child,
        Root $scraper
    ) {
        if (! empty($child['schema'])) {
            $branch = new Branch($name, $child['matcher'], $child['selector']);

            if (! empty($child['schema'])) {
                foreach ($child['schema'] as $subName => $subChild) {
                    static::addChildToScraper($subName, $subChild, $branch);
                }
            }

            $scraper->addChild($branch);
        } else {
            $leaf = new Leaf(
                $name,
                $child['matcher'],
                $child['extractor'],
                $child['normalizers'],
                $child['converters'],
                $child['selector']
            );

            $scraper->addChild($leaf);
        }
    }
}
