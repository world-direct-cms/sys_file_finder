<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'sys_file Finder Backend Module',
    'description' => 'This extension contains a backend module to show the path of a sys_file entry with a specific id. Also it can use fal_securedownload extension links to determine the sys_file id.',
    'category' => 'plugin',
    'author' => 'Klaus Hörmann-Engl',
    'author_email' => 'kho@world-direct.at',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '0.0.4',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
