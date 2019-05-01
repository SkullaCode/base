<?php


namespace Development\DBContext;


use App\DBContext\BaseDbContext;
use App\Model\StateCM;
use Psr\Container\ContainerInterface;

class State extends BaseDbContext
{
    private $JamaicaState;

    public function __construct(ContainerInterface $c)
    {
        parent::__construct(new StateCM(), $c);
    }

    public function FindAllStatesByCountry($country)
    {

    }

    public function FindAllStatesForJamaica()
    {
        return $this->FindAllStatesByCountry($this->JamaicaState);
    }
}