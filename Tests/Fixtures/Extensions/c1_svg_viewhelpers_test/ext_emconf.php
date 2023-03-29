<?php

declare(strict_types=1);

$EM_CONF[$_EXTKEY] = [
    'title' => 'Test support extension',
    'description' => '',
    'category' => 'Example Extensions',
    'author' => 'Manuel Munz',
    'author_email' => 't3dev@comuno.net',
    'author_company' => 'comuno.net',
    'state' => 'stable',
    'version' => '1.0.0',
    'autoload' => [
        'psr-4' => [
            'C1\\SvgViewhelpersTest\\' => 'Classes',
        ],
    ],
];
