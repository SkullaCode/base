<?php


namespace App\Interfaces;


interface IFileSystem
{
    public function Write($fileName,$content);

    public function OverWrite($fileName,$content);

    public function Read($fileName);

    public function Meta($fileName);

    public function Delete($fileName);

    public function DeleteDir($fileName);

    public function CleanDir($fileName);

    public function RecurseCopyDir($src,$dst);

    public function Purge();
}