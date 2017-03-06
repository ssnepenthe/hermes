<?php

return [
    'schema' => [
        [
            'extractor' => 'all:_text',
            'name' => 'results',
            'normalizers' => 'whitespace',
            'selector' => '.web-result',
        ],
    ],
];
