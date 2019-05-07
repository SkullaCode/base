<?php

//use App\Constant\PrivilegeCode;
use App\Constant\StatusCode;
use App\Extension\Extensions;

/*PrivilegeCode::init([
    PrivilegeCode::SUPER                        =>  "Super User",
    PrivilegeCode::ADMIN                        =>  "Administrator",
    PrivilegeCode::LIMITED                      =>  "Limited Access",
    PrivilegeCode::RESTRICTED                   =>  "Restricted Access"
],PrivilegeCode::class);*/

StatusCode::init([
    StatusCode::ACTIVE                          =>  "Active",
    StatusCode::INACTIVE                        =>  "Inactive",
    StatusCode::DELETED                         =>  "Deleted"
],StatusCode::class);

date_default_timezone_set('America/Jamaica');
Extensions::init($app->getContainer());
