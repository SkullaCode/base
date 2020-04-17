<?php

use Software\Entity\User\Constants\PrivilegeCode;
use Software\Entity\User\Constants\Status;

Status::init([
    Status::ACTIVE      =>  "Active",
    Status::LOCKED      =>  "Locked",
    Status::BANNED      =>  "Banned"
],Status::class);

PrivilegeCode::init([
    PrivilegeCode::ADMIN            =>  "Administrator",
    PrivilegeCode::ASSISTANT        =>  "Assistant"
],PrivilegeCode::class);