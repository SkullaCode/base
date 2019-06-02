<?php

use App\Constant\ModelMapping;
use Software\Query\Service as Query;

return [
    'route'         =>  'query',
    'middleware'    =>  [],
    'routes'        =>  [
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/brand/{id}',
            'class'         =>  Query::class,
            'action'        =>  'Brand',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/decoration/{id}',
            'class'         =>  Query::class,
            'action'        =>  'Decoration',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/gift-container/{id}',
            'class'         =>  Query::class,
            'action'        =>  'GiftContainer',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/gift-item/{id}',
            'class'         =>  Query::class,
            'action'        =>  'GiftItem',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/location/{id}',
            'class'         =>  Query::class,
            'action'        =>  'Location',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/package/{id}',
            'class'         =>  Query::class,
            'action'        =>  'Package',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/purchase/{id}',
            'class'         =>  Query::class,
            'action'        =>  'Purchase',
            'middleware'    =>  [

            ]
        ]
    ]
];
