<?php

return [
    'matchPattern' => 'duckduckgo.com',
    'schema' => [
        [
            'name' => 'header',
            'selector' => '.header__form',
            'schema' => [
                [
                    'attr' => 'value',
                    'name' => 'input',
                    'selector' => '.search__input',
                ],
            ],
        ],
    ],
];
