<?php

use App\Constant\ModelMapping;
use Software\Query\Service as Query;

return [
    'route'         =>  'query',
    'middleware'    =>  [],
    'routes'        =>  [
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/file/{id}',
            'class'         =>  Query::class,
            'action'        =>  'File',
            'middleware'    =>  []
        ]
    ]
];
