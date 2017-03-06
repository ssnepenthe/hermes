<?php

return [
    'matcher' => 'host:duckduckgo.com',
    'schema' => [
        [
            'converters' => 'bad-converter',
            'extractor' => 'first:href',
            'name' => 'icon',
            'selector' => '[rel="apple-touch-icon"]',
        ],
    ],
];
