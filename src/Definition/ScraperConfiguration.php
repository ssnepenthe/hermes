<?php

namespace SSNepenthe\Hermes\Definition;

use Closure;
use SSNepenthe\Hermes\Converter\ConverterStack;
use SSNepenthe\Hermes\Matcher\MatcherInterface;
use SSNepenthe\Hermes\Normalizer\NormalizerStack;
use SSNepenthe\Hermes\Converter\ConverterInterface;
use SSNepenthe\Hermes\Extractor\ExtractorInterface;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ScraperConfiguration implements ConfigurationInterface
{
    protected $aliases = [
        'converter' => [
            'to-interval' => \SSNepenthe\Hermes\Converter\ToInterval::class,
            'null' => \SSNepenthe\Hermes\Converter\NullConverter::class,
        ],
        'extractor' => [
            'all' => \SSNepenthe\Hermes\Extractor\All::class,
            'all-from-children' => \SSNepenthe\Hermes\Extractor\AllFromChildren::class,
            'first' => \SSNepenthe\Hermes\Extractor\First::class,
            'first-from-children' => \SSNepenthe\Hermes\Extractor\FirstFromChildren::class,
        ],
        'matcher' => [
            'document' => \SSNepenthe\Hermes\Matcher\DocumentMatcher::class,
            'host' => \SSNepenthe\Hermes\Matcher\HostMatcher::class,
            'null' => \SSNepenthe\Hermes\Matcher\NullMatcher::class,
            'selector' => \SSNepenthe\Hermes\Matcher\SelectorMatcher::class,
            'title' => \SSNepenthe\Hermes\Matcher\TitleMatcher::class,
            'url' => \SSNepenthe\Hermes\Matcher\UrlMatcher::class,
        ],
        'normalizer' => [
            'absolute-url' => \SSNepenthe\Hermes\Normalizer\AbsoluteUrl::class,
            'fraction' => \SSNepenthe\Hermes\Normalizer\Fraction::class,
            'horizontal-space' => \SSNepenthe\Hermes\Normalizer\HorizontalSpace::class,
            'consecutive-horizontal-space' => \SSNepenthe\Hermes\Normalizer\ConsecutiveHorizontalSpace::class,
            'consecutive-vertical-space' => \SSNepenthe\Hermes\Normalizer\ConsecutiveVerticalSpace::class,
            'null' => \SSNepenthe\Hermes\Normalizer\NullNormalizer::class,
            'whitespace' => \SSNepenthe\Hermes\Normalizer\Whitespace::class,
            'vertical-space' => \SSNepenthe\Hermes\Normalizer\VerticalSpace::class,
        ],
    ];
    protected $cached = [];
    protected $defaultConverter;
    protected $maxDepth;
    protected $defaultExtractor;
    protected $defaultMatcher;
    protected $defaultNormalizer;

    public function __construct(
        int $maxDepth = 5,
        ConverterInterface $defaultConverter = null,
        ExtractorInterface $defaultExtractor = null,
        MatcherInterface $defaultMatcher = null,
        NormalizerInterface $defaultNormalizer = null
    ) {
        $this->maxDepth = max(1, $maxDepth);
        $this->defaultConverter = $defaultConverter ?: $this->resolve(
            'null',
            'converter'
        );
        $this->defaultExtractor = $defaultExtractor ?: $this->resolve(
            'first:_text',
            'extractor'
        );
        $this->defaultMatcher = $defaultMatcher ?: $this->resolve('null', 'matcher');
        $this->defaultNormalizer = $defaultNormalizer ?: $this->resolve(
            'null',
            'normalizer'
        );
    }

    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder;
        $node = $builder->root('scraper');

        $this->addMatcherNode($node);
        $this->addSchemaNode($node);

        return $builder;
    }

    protected function addMatcherNode(ArrayNodeDefinition $node)
    {
        $node->children()
            ->variableNode('matcher')
                ->defaultValue($this->defaultMatcher)
                ->beforeNormalization()
                    ->always()
                    ->then(function ($value) {
                        return $this->resolve($value, 'matcher');
                    })
                ->end()
                ->validate()
                    ->ifTrue(function ($value) {
                        return ! $value instanceof MatcherInterface;
                    })
                    ->thenInvalid('Invalid matcher %s')
                ->end()
            ->end()
        ->end();
    }

    protected function addSchemaNode(
        ArrayNodeDefinition $rootNode,
        int $currentDepth = 0
    ) {
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

        $this->addMatcherNode($schemaNode);

        if ($currentDepth < $this->maxDepth) {
            $this->addSchemaNode($schemaNode, $currentDepth);
        }

        $schemaNode
            ->children()
                ->variableNode('converters')
                    ->defaultValue($this->defaultConverter)
                    ->beforeNormalization()
                        ->always()
                        ->then(function ($values) {
                            $normalized = array_map(function ($value) {
                                return $this->resolve($value, 'converter');
                            }, (array) $values);

                            if (empty($normalized)) {
                                return $this->defaultConverter;
                            }

                            if (1 === count($normalized)) {
                                return reset($normalized);
                            }

                            return new ConverterStack($normalized);
                        })
                    ->end()
                    ->validate()
                        ->ifTrue(function ($value) {
                            return ! $value instanceof ConverterInterface;
                        })
                        ->thenInvalid('Invalid converter %s')
                    ->end()
                ->end()
                ->variableNode('extractor')
                    ->defaultValue($this->defaultExtractor)
                    ->beforeNormalization()
                        ->always()
                        ->then(function ($value) {
                            return $this->resolve($value, 'extractor');
                        })
                    ->end()
                    ->validate()
                        ->ifTrue(function ($value) {
                            return ! $value instanceof ExtractorInterface;
                        })
                        ->thenInvalid('Invalid extractor %s')
                    ->end()
                ->end()
                ->variableNode('normalizers')
                    ->defaultValue($this->defaultNormalizer)
                    ->beforeNormalization()
                        ->always()
                        ->then(function ($values) {
                            $normalized = array_map(function ($value) {
                                return $this->resolve($value, 'normalizer');
                            }, (array) $values);

                            if (empty($normalized)) {
                                return $this->defaultNormalizer;
                            }

                            if (1 === count($normalized)) {
                                return reset($normalized);
                            }

                            return new NormalizerStack($normalized);
                        })
                    ->end()
                    ->validate()
                        ->ifTrue(function ($value) {
                            return ! $value instanceof NormalizerInterface;
                        })
                        ->thenInvalid('Invalid normalizer %s')
                    ->end()
                ->end()
                ->scalarNode('selector')
                    ->defaultValue('')
                ->end()
                ->enumNode('type')
                    ->defaultValue('singular')
                    ->values(['plural', 'singular'])
                ->end()
            ->end();
    }

    // @todo Such nesting...
    protected function resolve($value, $type)
    {
        if (! isset($this->aliases[$type])) {
            return $value;
        }

        $ucfirst = ucfirst($type);

        if ($value instanceof Closure) {
            $wrapper = sprintf('SSNepenthe\\Hermes\\%1$s\\Closure%1$s', $ucfirst);

            return new $wrapper($value);
        }

        if (is_string($value)) {
            $colonPos = strpos($value, ':');
            $interface = sprintf(
                'SSNepenthe\\Hermes\\%1$s\\%1$sInterface',
                $ucfirst
            );

            if (false === $colonPos) {
                $classname = $this->aliases[$type][$value] ?? $value;

                if (isset($this->cached[$classname])) {
                    return $this->cached[$classname];
                }

                $implements = class_exists($classname)
                    ? class_implements($classname)
                    : [];

                if (isset($implements[$interface])) {
                    return $this->cached[$classname] = new $classname;
                }
            } else {
                $alias = substr($value, 0, $colonPos);
                $params = substr($value, $colonPos + 1);
                $classname = $this->aliases[$type][$alias] ?? $alias;
                $key = $classname . ':' . $params;

                if (isset($this->cached[$key])) {
                    return $this->cached[$key];
                }

                $implements = class_exists($classname)
                    ? class_implements($classname)
                    : [];

                if (isset($implements[$interface])) {
                    return $this->cached[$key] = new $classname($params);
                }
            }
        }

        // Otherwise just return as provided - validation will sort it out later.
        return $value;
    }
}
