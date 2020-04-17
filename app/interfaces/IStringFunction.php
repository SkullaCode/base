<?php


namespace App\Interfaces;


interface IStringFunction
{
    /**
     * @param string $string
     * @return string
     */
    public function Pluralize($string);

    /**
     * @param string $string
     * @return string
     */
    public function CapitalizeFirstLetter($string);
}