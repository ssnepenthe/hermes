<?php

return [
    'schema' => [
        [
            'extractor' => 'first-from-children:_text',
            'name' => 'recipeIngredients',
            'selector' => '[itemprop="ingredients"]',
        ],
    ],
];
