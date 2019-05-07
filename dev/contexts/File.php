<?php


namespace Development\DBContext;


use App\DBContext\BaseDbContext;
use Psr\Container\ContainerInterface;
use Software\File\IContext;
use Software\File\Model;

class File extends BaseDbContext implements IContext
{
    public function __construct(ContainerInterface $c)
    {
        parent::__construct(new Model(), $c);
    }
}