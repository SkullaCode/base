<?php

use App\Constant\ModelMapping;
use Software\Settings\MiddleWare\GetSettingsFromRequest;
use Software\Settings\Service as Service;


return [
    'route'         =>  'settings',
    'middleware'    =>  [

    ],
    'access'        =>  [

    ],
    'routes'        =>  [
        [
            'method'        =>  ModelMapping::UPDATING,
            'url'           =>  '/',
            'class'         =>  Service::class,
            'action'        =>  'Save',
            'middleware'    =>  [
                GetSettingsFromRequest::class
            ],
            'access'        =>  [

            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/',
            'class'         =>  Service::class,
            'action'        =>  'Load'
        ]
    ]
];
