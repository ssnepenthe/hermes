<?php

namespace SSNepenthe\Hermes\Definition;

use SSNepenthe\Hermes\Extractor\First;
use SSNepenthe\Hermes\Converter\ConverterInterface;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ScraperConfiguration implements ConfigurationInterface
{
    /**
     * Arbitrary default.
     */
    protected $maxDepth = 5;
    protected $normalizers = [];
    protected $converters = [];
    // @todo
    protected $extractor;

    public function __construct()
    {
        $this->extractor = new First('_text');
    }

    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder;
        $node = $builder->root('scraper');

        $node->children()
            ->enumNode('matchType')
                ->defaultValue('host')
                ->values([
                    'host',
                    'url',
                    'title',
                    'document',
                    'selector',
                ])
            ->end()
            ->scalarNode('matchPattern')
                ->cannotBeEmpty()
                ->isRequired()
            ->end()
        ->end();

        $this->addSchemaNode($node);

        return $builder;
    }

    public function setDefaultConverters(array $converters)
    {
        $this->converters = [];

        foreach ($converters as $converter) {
            $this->addDefaultConverter($this->prepareCallable($converter));
        }
    }

    public function setDefaultNormalizers(array $normalizers)
    {
        $this->normalizers = [];

        foreach ($normalizers as $normalizer) {
            $this->addDefaultNormalizer($this->prepareCallable($normalizer));
        }
    }

    public function setMaxDepth(int $depth)
    {
        $this->maxDepth = $depth;
    }

    protected function addSchemaNode(ArrayNodeDefinition $rootNode, int $currentDepth = 0)
    {
        $currentDepth += 1;

        $schemaNode = $rootNode->children()
            ->arrayNode('schema');

        if (1 === $currentDepth) {
            $schemaNode->isRequired();
        }

        // @todo Verify at least one element is working, no empty elements.
        $schemaNode = $schemaNode
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array');

        if ($currentDepth < $this->maxDepth) {
            $this->addSchemaNode($schemaNode, $currentDepth);
        }

        $schemaNode
            ->children()
                ->scalarNode('attr')
                    ->cannotBeEmpty()
                    ->defaultValue('_text')
                ->end()
                ->variableNode('extractor')
                    ->defaultValue($this->prepareCallable($this->extractor))
                    ->beforeNormalization()
                        ->always()
                        ->then(function ($v) { return $this->prepareCallable($v); })
                    ->end()
                    ->validate()
                        ->ifTrue(function ($v) { return ! is_callable($v); })
                        ->thenInvalid('Invalid extractor %s')
                    ->end()
                ->end()
                ->arrayNode('normalizers')
                    ->prototype('variable')->end()
                    ->defaultValue($this->normalizers)
                    ->beforeNormalization()
                        ->always()
                        ->then(function ($v) {
                            $normalized = $this->normalizers;

                            foreach ((array) $v as $value) {
                                $normalized[] = $this->prepareCallable($value);
                            }

                            return $normalized;
                        })
                    ->end()
                    ->validate()
                        ->ifTrue(function ($v) {
                            $nonCallables = array_filter(array_map(function ($val) {
                                return ! is_callable($val);
                            }, $v));

                            return ! empty($nonCallables);
                        })
                        // @todo
                        ->thenInvalid('Invalid normalizer %s')
                    ->end()
                ->end()
                ->scalarNode('selector')
                    ->defaultValue('')
                ->end()
                ->arrayNode('converters')
                    ->prototype('variable')->end()
                    ->defaultValue($this->converters)
                    ->beforeNormalization()
                        ->always()
                        ->then(function ($v) {
                            $normalized = $this->converters;

                            foreach ((array) $v as $value) {
                                $normalized[] = $this->prepareCallable($value);
                            }

                            return $normalized;
                        })
                    ->end()
                    ->validate()
                        ->ifTrue(function ($v) {
                            $nonCallables = array_filter(array_map(function ($val) {
                                return ! is_callable($val);
                            }, $v));

                            return ! empty($nonCallables);
                        })
                        // @todo
                        ->thenInvalid('Invalid converter %s')
                    ->end()
                ->end()
            ->end();
    }

    protected function addDefaultConverter(callable $converter)
    {
        $this->converters[] = $converter;
    }

    protected function addDefaultNormalizer(callable $normalizer)
    {
        $this->normalizers[] = $normalizer;
    }

    protected function prepareCallable($callable)
    {
        if (is_callable($callable)) {
            return $callable;
        }

        if (is_string($callable)) {
            if (class_exists($callable) && method_exists($callable, '__invoke')) {
                return new $callable;
            }

            if (2 === count($parts = explode('@', $callable))) {
                list($class, $method) = $parts;

                if (class_exists($class) && method_exists($class, $method)) {
                    return [new $class, $method];
                }
            }
        }

        // No identifiable callable in the given value - return as-is and let the
        // validation portion of our config definition throw as needed.
        return $callable;
    }
}
