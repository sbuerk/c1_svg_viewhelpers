<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'C1 SVG Viewhelpers',
    'description' => 'Viewhelpers for SVG in fluid, i.e. allows to reference external SVG from a symbol file',
    'category' => 'frontend',
    'author' => 'Manuel Munz',
    'author_email' => 't3dev@comuno.net',
    'author_company' => 'comuno.net',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '1.0.0',
    'autoload' => [
        'psr-4' => [
            'C1\\C1SvgViewHelpers\\' => 'Classes',
        ],
    ],
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.1.99'
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
