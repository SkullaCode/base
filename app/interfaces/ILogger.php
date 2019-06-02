<?php


namespace App\Interfaces;

interface ILogger
{
    public function AddSubject($subject);

    public function AddMessage($message);

    public function AddRecipient($email);

    public function AddContent($content);

    public function SaveLog();

    public function GetDebugInfo();

    public function Refresh();
}