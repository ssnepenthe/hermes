<?php

return [
    'schema' => [
        [
            'name' => 'results',
            'selector' => '.web-result',
            'schema' => [
                [
                    'name' => 'title',
                    'selector' => '.result__title',
                ],
                [
                    'extractor' => 'first:href',
                    'name' => 'url',
                    'selector' => '.result__url',
                ],
                [
                    'name' => 'description',
                    'selector' => '.result__snippet',
                ],
            ],
        ],
    ],
];
