<?php

return [
    'matcher' => 'host:duckduckgo.com',
    'schema' => [
        [
            'converters' => 'to-interval',
            'extractor' => 'all-from-children:_text',
            'matcher' => 'selector:.some-wrapper-class',
            'name' => 'icon',
            'normalizers' => 'fraction',
            'selector' => '[rel="apple-touch-icon"]',
        ],
    ],
];
