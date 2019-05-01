<?php


namespace App\Interfaces\Context;


interface IState extends IDbContext
{
    public function FindAllStatesByCountry($country);

    public function FindAllStatesForJamaica();
}