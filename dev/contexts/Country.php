<?php


namespace Development\DBContext;


use App\DBContext\BaseDbContext;
use App\Model\CountryCM;
use Psr\Container\ContainerInterface;

class Country extends BaseDbContext
{
    public function __construct(ContainerInterface $c)
    {
        parent::__construct(new CountryCM(),$c);
        $this->Table = "countries";
    }

    public function GetAll()
    {
        return [];
    }
}