<?php


namespace App\Interfaces;


interface ICodeGenerator
{
    public function ResetAccount();

    public function Cookie();

    public function AlphaString($length);

    public function AlphaNumericString($length);

    public function SessionToken();

    public function ResourceHash();
}