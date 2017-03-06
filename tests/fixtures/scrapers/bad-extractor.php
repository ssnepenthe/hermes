<?php

return [
    'matcher' => 'host:duckduckgo.com',
    'schema' => [
        [
            'extractor' => 'bad-extractor:href',
            'name' => 'icon',
            'selector' => '[rel="apple-touch-icon"]',
        ],
    ],
];
