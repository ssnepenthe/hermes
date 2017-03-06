<?php

return [
    'matcher' => 'host:duckduckgo.com',
    'schema' => [
        [
            'name' => 'results',
            'selector' => '.results-wrapper',
            'schema' => [
                [
                    'name' => 'result',
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
        ],
    ],
];
