<?php

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'sysfilefinder',
    'file',
    'find',
    'top',
    [
        \WorldDirect\Sysfilefinder\Controller\SysfilefinderController::class => 'index'
    ],
    [
        'access' => 'user,group',
        'icon' => 'EXT:sysfilefinder/Resources/Public/Icons/module-sysfilefinder.svg',
        'labels' => 'LLL:EXT:sysfilefinder/Resources/Private/Language/locallang_mod.xlf',
        'inheritNavigationComponentFromMainModule' => false
    ]
);

// Load static TS template
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('sysfilefinder', 'Configuration/TypoScript', 'sysfilefinder');
