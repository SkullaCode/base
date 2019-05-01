<?php


namespace Development\DBContext;

use App\DBContext\BaseDbContext;
use App\Model\FileCM;
use Psr\Container\ContainerInterface;

class File extends BaseDbContext
{
    public function __construct(ContainerInterface $c)
    {
        parent::__construct(new FileCM(),$c);
    }
}