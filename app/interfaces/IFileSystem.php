<?php


namespace App\Interfaces;


interface IFileSystem
{
    public function Write($fileName,$content);

    public function OverWrite($fileName,$content);

    public function Read($fileName);

    public function Meta($fileName);

    public function Delete($fileName);

    public function Purge();
}