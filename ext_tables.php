<?php

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'SysFileFinder',
    'file',
    'find',
    'top',
    [
        \WorldDirect\SysFileFinder\Controller\SysFileFinderController::class => 'index'
    ],
    [
        'access' => 'user,group',
        'icon' => 'EXT:sys_file_finder/Resources/Public/Icons/module-sysfilefinder.svg',
        'labels' => 'LLL:EXT:sys_file_finder/Resources/Private/Language/locallang_mod.xlf',
        'inheritNavigationComponentFromMainModule' => false
    ]
);

// Load static TS template
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('sys_file_finder', 'Configuration/TypoScript', 'sys_file_finder');
