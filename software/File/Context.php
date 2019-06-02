<?php


namespace Software\File;


use App\DBContext\BaseDbContext;
use Psr\Container\ContainerInterface;

class Context extends BaseDbContext
{
    public function __construct(ContainerInterface $c)
    {
        parent::__construct(new Model(), \Software\Provider\Context::FILE_TABLE, $c);
    }
}