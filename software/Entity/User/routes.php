<?php
$route = 'user';

use App\Constant\ModelMapping;
use App\MiddleWare\Validation\SessionExists;
use Software\Entity\User\Constants\PrivilegeCode;
use Software\Entity\User\MiddleWare\LoadUserFromId;
use Software\Entity\User\MiddleWare\NormalizeProfilePicture;
use Software\Entity\User\MiddleWare\NormalizeSelectorToken;
use Software\Entity\User\MiddleWare\NormalizeUserCreation;
use Software\Entity\User\MiddleWare\NormalizeValidationParameters;
use Software\Entity\User\MiddleWare\ValidateRegistration;
use Software\Entity\User\MiddleWare\ValidateUserCreation;
use Software\Entity\User\MiddleWare\ViewModelFilter;
use Software\Entity\User\Service as Route;

return [
    'route'         =>  $route,
    'middleware'    =>  [],
    'access'        =>  [

    ],
    'routes'        =>  [
        [
            'method'        =>  ModelMapping::CREATING,
            'url'           =>  '',
            'class'         =>  Route::class,
            'action'        =>  'Create',
            'middleware'    =>  [
                ValidateUserCreation::class,
                NormalizeUserCreation::class,
                SessionExists::class
            ],
            'access'        =>  [
                PrivilegeCode::ADMIN
            ]
        ],
        [
            'method'        =>  ModelMapping::UPDATING,
            'url'           =>  '/profile/picture',
            'class'         =>  Route::class,
            'action'        =>  'UploadProfilePic',
            'middleware'    =>  [
                NormalizeProfilePicture::class,
                LoadUserFromId::class,
                SessionExists::class
            ],
            'access'        =>  [
            ]
        ],
        [
            'method'        =>  ModelMapping::UPDATING,
            'url'           =>  '/profile/password',
            'class'         =>  Route::class,
            'action'        =>  'UpdatePassword',
            'middleware'    =>  [
                LoadUserFromId::class,
                SessionExists::class
            ],
            'access'        =>  [
            ]
        ],
        [
            'method'        =>  ModelMapping::UPDATING,
            'url'           =>  '/profile',
            'class'         =>  Route::class,
            'action'        =>  'Update',
            'middleware'    =>  [
                LoadUserFromId::class,
                SessionExists::class
            ],
            'access'        =>  [
            ]
        ],
        [
            'method'        =>  ModelMapping::UPDATING,
            'url'           =>  '/validate',
            'class'         =>  Route::class,
            'action'        =>  'Validate',
            'middleware'    =>  [
                NormalizeValidationParameters::class
            ]
        ],
        [
            'method'        =>  ModelMapping::UPDATING,
            'url'           =>  '/register',
            'class'         =>  Route::class,
            'action'        =>  'Register',
            'middleware'    =>  [
                ValidateRegistration::class
            ]
        ],
        [
            'method'        =>  ModelMapping::UPDATING,
            'url'           =>  '/reset-account',
            'class'         =>  Route::class,
            'action'        =>  'ResetAccount',
            'middleware'    =>  [
                NormalizeValidationParameters::class
            ]
        ],
        [
            'method'        =>  ModelMapping::UPDATING,
            'url'           =>  '/resend-reset-account',
            'class'         =>  Route::class,
            'action'        =>  'ResendResetAccount',
            'middleware'    =>  [
                NormalizeValidationParameters::class
            ]
        ],
        [
            'method'        =>  ModelMapping::UPDATING,
            'url'           =>  '/change-password',
            'class'         =>  Route::class,
            'action'        =>  'ChangePassword',
            'middleware'    =>  [
                NormalizeValidationParameters::class,
                NormalizeSelectorToken::class
            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/auth/recover',
            'class'         =>  Route::class,
            'action'        =>  'ConfirmEmail',
            'middleware'    =>  [
               NormalizeSelectorToken::class
            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/auth/verify',
            'class'         =>  Route::class,
            'action'        =>  'VerifyAccount',
            'middleware'    =>  [
                NormalizeSelectorToken::class
            ]
        ],
        [
            'method'        =>  ModelMapping::UPDATING,
            'url'           =>  '/role',
            'class'         =>  Route::class,
            'action'        =>  'AddRole',
            'middleware'    =>  [
                LoadUserFromId::class,
                SessionExists::class
            ],
            'access'        =>  [
                PrivilegeCode::ADMIN
            ]
        ],
        [
            'method'        =>  ModelMapping::DELETING,
            'url'           =>  '/role',
            'class'         =>  Route::class,
            'action'        =>  'RemoveRole',
            'middleware'    =>  [
                LoadUserFromId::class,
                SessionExists::class
            ],
            'access'        =>  [
                PrivilegeCode::ADMIN
            ]
        ],
        [
            'method'        =>  ModelMapping::UPDATING,
            'url'           =>  '/status/enable',
            'class'         =>  Route::class,
            'action'        =>  'Enable',
            'middleware'    =>  [
                LoadUserFromId::class,
                SessionExists::class
            ],
            'access'        =>  [
                PrivilegeCode::ADMIN
            ]
        ],
        [
            'method'        =>  ModelMapping::UPDATING,
            'url'           =>  '/status/lock',
            'class'         =>  Route::class,
            'action'        =>  'Disable',
            'middleware'    =>  [
                LoadUserFromId::class,
                SessionExists::class
            ],
            'access'        =>  [
                PrivilegeCode::ADMIN
            ]
        ],
        [
            'method'        =>  ModelMapping::UPDATING,
            'url'           =>  '/status/ban',
            'class'         =>  Route::class,
            'action'        =>  'Ban',
            'middleware'    =>  [
                LoadUserFromId::class,
                SessionExists::class
            ],
            'access'        =>  [
                PrivilegeCode::ADMIN
            ]
        ],
        [
            'method'        =>  ModelMapping::READING,
            'url'           =>  '/{id}',
            'class'         =>  Route::class,
            'action'        =>  'Detail',
            'middleware'    =>  [
                ViewModelFilter::class,
                SessionExists::class
            ],
            'access'        =>  [
                PrivilegeCode::ADMIN
            ]
        ],
        [
            'method'        =>  ModelMapping::DELETING,
            'url'           =>  '/{id}',
            'class'         =>  Route::class,
            'action'        =>  'Delete',
            'middleware'    =>  [
                SessionExists::class
            ],
            'access'        =>  [
                PrivilegeCode::ADMIN
            ]
        ],
    ]
];