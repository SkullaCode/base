<?php


namespace Software\Hooks;


use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class Load
{
    private $container;

    public function __construct(ContainerInterface $c)
    {
        $this->container = $c;
    }

    public function __invoke(Request &$rq)
    {

    }
}