<?php


namespace App\DBContext;


use App\Model\SessionCM;
use Psr\Container\ContainerInterface;

class Session extends BaseDbContext
{
    public function __construct(ContainerInterface $c)
    {
        parent::__construct(new SessionCM(), $c);
        $this->Table = "sessions";
    }
}