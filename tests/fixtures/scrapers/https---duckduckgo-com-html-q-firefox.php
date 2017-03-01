<?php

return [
    'matchPattern' => 'duckduckgo.com',
    'schema' => [
        [
            'name' => 'results',
            'selector' => '.result',
            'schema' => [
                [
                    'name' => 'title',
                    'selector' => '.result__title',
                    'normalizers' => [
                        SSNepenthe\Hermes\Normalizer\ConsecutiveVerticalSpace::class,
                        SSNepenthe\Hermes\Normalizer\ConsecutiveHorizontalSpace::class,
                    ],
                ],
                [
                    'attr' => 'href',
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
