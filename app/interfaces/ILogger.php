<?php


namespace App\Interfaces;


use Psr\Log\LoggerInterface;

interface ILogger extends LoggerInterface
{
    public function reset();
}