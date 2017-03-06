<?php

return [
    'schema' => [
        [
            'extractor' => 'first:_text',
            'name' => 'results',
            'normalizers' => 'whitespace',
            'selector' => '.web-result',
        ],
    ],
];
