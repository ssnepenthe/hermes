<?php

return [
    'matcher' => 'host:duckduckgo.com',
    'schema' => [
        [
            'converters' => SSNepenthe\Hermes\Converter\ToInterval::class,
            'extractor' => SSNepenthe\Hermes\Extractor\AllFromChildren::class . ':_text',
            'matcher' => SSNepenthe\Hermes\Matcher\SelectorMatcher::class . ':.some-wrapper-class',
            'name' => 'icon',
            'normalizers' => SSNepenthe\Hermes\Normalizer\Fraction::class,
            // 'selector' => '[rel="apple-touch-icon"]',
        ],
    ],
];
