<?php

return [
    'matcher' => 'host:duckduckgo.com',
    'schema' => [
        [
            'normalizers' => 'bad-normalizer',
            'extractor' => 'first:href',
            'name' => 'icon',
            'selector' => '[rel="apple-touch-icon"]',
        ],
    ],
];
