<?php


namespace Software\Entity\User\Constants;


use App\Constant\CodeBase;

class Status extends CodeBase
{
    const ACTIVE    = \Delight\Auth\Status::NORMAL;
    const LOCKED    = \Delight\Auth\Status::LOCKED;
    const BANNED    = \Delight\Auth\Status::BANNED;
    const DELETED   = \Delight\Auth\Status::ARCHIVED;
}