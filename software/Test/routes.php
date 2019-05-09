<?php

use App\Constant\ModelMapping;
use Software\Test\Service as Test;

return [
        'route'         =>  'test',
        'middleware'    =>  [],
        'routes'        =>  [
            [
                'method'        =>  ModelMapping::READING,
                'url'           =>  '/utility/request',
                'class'         =>  Test::class,
                'action'        =>  'RequestUtility',
                'middleware'    =>  []
            ]
        ]
    ];