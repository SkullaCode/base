<?php


namespace App\Interfaces;


interface IRequest
{
    public function UserAgent();

    public function HostName();

    public function BaseURL($slug="");

    public function IPAddress();

    public function IsAjaxRequest();
}