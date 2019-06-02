<?php


namespace App\Interfaces;


interface IPassword
{
    public function Hash($password);

    public function Verify($password, $hash);
}