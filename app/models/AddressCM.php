<?php


namespace App\Model;


class AddressCM extends AuditCM
{
    /**
     * @var string Street address etc.
     */
    public $AddressLine1;

    /**
     * @var string Community etc.
     */
    public $AddressLine2;

    /**
     * @var string City.
     */
    public $City;

    /**
     * @var string State ID.
     */
    public $State;

    /**
     * @var string Postal or zip code.
     */
    public $PostalZipCode;
}