<?php

use App\Constant\ContactNumberCode;
use App\Constant\GenderCode;
use App\Constant\MaritalStatusCode;
use App\Constant\PrivilegeCode;
use App\Constant\TitleCode;
use App\Constant\StatusCode;


ContactNumberCode::init([
    ContactNumberCode::HOME                     =>  "Home",
    ContactNumberCode::WORK                     =>  "Work",
    ContactNumberCode::MOBILE                   =>  "Mobile",
    ContactNumberCode::FAX                      =>  "Fax"
],ContactNumberCode::class);

GenderCode::init([
    GenderCode::MALE                            =>  "Male",
    GenderCode::FEMALE                          =>  "Female",
    GenderCode::UNSPECIFIED                     =>  "Unspecified"
],GenderCode::class);

MaritalStatusCode::init([
    MaritalStatusCode::SINGLE                   =>  "Single",
    MaritalStatusCode::MARRIED                  =>  "Married",
    MaritalStatusCode::DIVORCED                 =>  "Divorced",
    MaritalStatusCode::WIDOWED                  =>  "Widowed"
],MaritalStatusCode::class);

PrivilegeCode::init([
    PrivilegeCode::SUPER                        =>  "Super User",
    PrivilegeCode::ADMIN                        =>  "Administrator",
    PrivilegeCode::LIMITED                      =>  "Limited Access",
    PrivilegeCode::RESTRICTED                   =>  "Restricted Access"
],PrivilegeCode::class);

TitleCode::init([
    TitleCode::MR                               =>  "Mr.",
    TitleCode::MISS                             =>  "Miss.",
    TitleCode::MRS                              =>  "Mrs.",
    TitleCode::DR                               =>  "Dr."
],TitleCode::class);

StatusCode::init([
    StatusCode::ACTIVE                          =>  "Active",
    StatusCode::INACTIVE                        =>  "Inactive",
    StatusCode::DELETED                         =>  "Deleted"
],StatusCode::class);

date_default_timezone_set('America/Jamaica');
\App\Extension\Extensions::init($app->getContainer());
