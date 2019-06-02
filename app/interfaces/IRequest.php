<?php


namespace App\Interfaces;


interface IRequest
{
    public function UserAgent();

    public function HostName();

    public function BaseURL();

    public function IPAddress();
}