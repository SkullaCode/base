<?php

use App\Constant\ModelMapping;
use Software\Lists\Service as ListsService;

return [
    'route'         =>  'list',
    'middleware'    =>  [],
    'routes'        =>  [
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/contact-number',
            'class'         =>  ListsService::class,
            'action'        =>  'ContactNumber',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/gender',
            'class'         =>  ListsService::class,
            'action'        =>  'Gender',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/marital-status',
            'class'         =>  ListsService::class,
            'action'        =>  'Marital',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/title',
            'class'         =>  ListsService::class,
            'action'        =>  'Title',
            'middleware'    =>  [

            ]
        ]
    ]
];
