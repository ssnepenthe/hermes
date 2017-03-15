<?php

return [
    'schema' => [
        [
            'extractor' => 'first-split:_text',
            'name' => 'results',
            'selector' => '.web-result',
            'type' => 'plural',
        ],
    ],
];
