<?php

return [
    'schema' => [
        [
            'name' => 'header',
            'selector' => '.header__form',
            'schema' => [
                [
                    'extractor' => 'first:value',
                    'name' => 'input',
                    'selector' => '.search__input',
                ],
            ],
        ],
    ],
];
