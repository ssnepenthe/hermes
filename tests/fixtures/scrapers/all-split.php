<?php

return [
    'schema' => [
        [
            'extractor' => 'all-split:_text',
            'name' => 'results',
            'selector' => '.web-result',
            'type' => 'plural',
        ],
    ],
];
