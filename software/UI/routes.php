<?php

use App\Constant\ModelMapping;
use App\MiddleWare\Validation\SessionExists;
use Software\UI\LandingPage;

return [
    'route'         =>  '',
    'middleware'    =>  [],
    'routes'        =>  [
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  'login',
            'class'         =>  LandingPage::class,
            'action'        =>  'Login',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  'log-out',
            'class'         =>  LandingPage::class,
            'action'        =>  'Logout',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  'user',
            'class'         =>  LandingPage::class,
            'action'        =>  'User',
            'middleware'    =>  [
                SessionExists::class
            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  'change-password',
            'class'         =>  LandingPage::class,
            'action'        =>  'ChangePassword',
            'middleware'    =>  [

            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '',
            'class'         =>  LandingPage::class,
            'action'        =>  'Index',
            'middleware'    =>  [
                SessionExists::class
            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  'profile',
            'class'         =>  LandingPage::class,
            'action'        =>  'Profile',
            'middleware'    =>  [
                SessionExists::class
            ]
        ],
    ]
];