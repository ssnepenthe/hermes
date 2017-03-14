<?php

return [
    'schema' => [
        [
            'extractor' => 'all-from-children:_text',
            'name' => 'recipeIngredients',
            'selector' => '[itemprop="ingredients"]',
            'type' => 'plural',
        ],
    ],
];
