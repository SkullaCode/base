<?php

use App\Constant\ModelMapping;
use Software\File\Service as File;

return [
        'route'         =>  'file',
        'middleware'    =>  [],
        'routes'        =>  [
            [
                'method'        =>  ModelMapping::CREATING,
                'url'           =>  '/',
                'class'         =>  File::class,
                'action'        =>  'Create',
                'middleware'    =>  []
            ],
            [
                'method'        =>  ModelMapping::UPDATING,
                'url'           =>  '/',
                'class'         =>  File::class,
                'action'        =>  'Update',
                'middleware'    =>  []
            ],
            [
                'method'        =>  ModelMapping::DELETING,
                'url'           =>  '/',
                'class'         =>  File::class,
                'action'        =>  'Delete',
                'middleware'    =>  []
            ],
        ]
    ];
