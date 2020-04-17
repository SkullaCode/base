<?php


namespace App\Utility;


use App\Interfaces\IStringFunction;

class StringFunction implements IStringFunction
{

    /**
     * @param string $string
     * @return string
     */
    public function Pluralize($string)
    {
        $parts = explode(" ",$string);
        $parts = array_map(function($elem){
            return "{$elem}s";
        },$parts);
        $string = implode(" ",$parts);
        return $string;
    }

    /**
     * @param string $string
     * @return string
     */
    public function CapitalizeFirstLetter($string)
    {
        $parts = explode(" ",$string);
        $parts = array_map(function($elem){
            return ucfirst($elem);
        },$parts);
        $string = implode(" ",$parts);
        return $string;
    }
}