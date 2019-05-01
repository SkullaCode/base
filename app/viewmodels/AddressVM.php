<?php


namespace App\ViewModel;


use App\ViewModel\Def\Descriptor;

class AddressVM
{
    /**
     * @var int
     */
    public $ID;

    /**
     * @var string
     */
    public $AddressLine1;

    /**
     * @var string
     */
    public $AddressLine2;

    /**
     * @var string
     */
    public $AddressCity;

    /**
     * @var Descriptor
     */
    public $AddressState;

    /**
     * @var Descriptor
     */
    public $AddressCountry;
}