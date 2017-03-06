<?php

return [
    'matcher' => 'host:duckduckgo.com',
    'schema' => [
        [
            'converters' => [function() {}, 'to-interval'],
            'name' => 'icon',
            'normalizers' => ['absolute-url', 'fraction'],
            'selector' => '[rel="apple-touch-icon"]',
        ],
    ],
];
