<?php

use Software\Lists\Service as ListsService;

return [
    'route'         =>  'list',
    'middleware'    =>  [],
    'routes'    =>  [
        [
            'method'        =>  'get',
            'url'           =>  '/contact-number',
            'class'         =>  ListsService::class,
            'action'        =>  'ContactNumber',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  'get',
            'url'           =>  '/gender',
            'class'         =>  ListsService::class,
            'action'        =>  'Gender',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  'get',
            'url'           =>  '/marital-status',
            'class'         =>  ListsService::class,
            'action'        =>  'Marital',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  'get',
            'url'           =>  '/title',
            'class'         =>  ListsService::class,
            'action'        =>  'Title',
            'middleware'    =>  [

            ]
        ]
    ]
];
