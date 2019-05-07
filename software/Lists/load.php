<?php


use Software\Lists\Constant\ContactNumberCode;
use Software\Lists\Constant\GenderCode;
use Software\Lists\Constant\MaritalStatusCode;
use Software\Lists\Constant\TitleCode;

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

TitleCode::init([
    TitleCode::MR                               =>  "Mr.",
    TitleCode::MISS                             =>  "Miss.",
    TitleCode::MRS                              =>  "Mrs.",
    TitleCode::DR                               =>  "Dr."
],TitleCode::class);
