<?php

use WorldDirect\Sysfilefinder\Controller\SysfilefinderController;

return [
    'admin_sysfilefinder' => [
        'parent' => 'file',
        'access' => 'user',
        'position' => ['after' => 'web_info'],
        'path' => '/module/file/sysfilefinder',
        'labels' => 'LLL:EXT:sysfilefinder/Resources/Private/Language/locallang_mod.xlf',
        'extensionName' => 'sysfilefinder',
        'iconIdentifier' => 'tx-sysfilefinder-module',
        'controllerActions' => [
            SysfilefinderController::class => [
                'index'
            ],
        ],
    ],
];
