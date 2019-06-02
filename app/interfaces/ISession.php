<?php


namespace App\Interfaces;

interface ISession
{
    public function GetItem($id,$default=null);

    public function GetFlashItem($id,$default=null);

    public function SetItem($id,$value);

    public function SetFlashItem($id,$value);

    public function DeleteItem($id);

    public function Lock();

    public function Destroy();
}