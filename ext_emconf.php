<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'C1 SVG Viewhelpers',
    'description' => 'Viewhelpers for SVG in fluid, i.e. allows to reference external SVG from a symbol file',
    'category' => 'frontend',
    'author' => 'Manuel Munz',
    'author_email' => 't3dev@comuno.net',
    'author_company' => 'comuno.net',
    'state' => 'stable',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'version' => '1.0.0',
    'autoload' => [
        'psr-4' => [
            'C1\\C1SvgViewHelpers\\' => 'Classes',
        ],
    ],
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99'
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
