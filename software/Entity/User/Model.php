<?php


namespace Software\Entity\User;



class Model
{
    public $ID;
    public $UserName;
    public $EmailAddress;
    public $Password;
    public $Verified;
    public $Status;
    public $Resettable;
    public $RolesMask;
    public $Registered;
    public $LastLogin;
    public $ForceLogout;
}