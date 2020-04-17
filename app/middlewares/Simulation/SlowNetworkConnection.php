<?php


namespace App\MiddleWare\Simulation;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class SlowNetworkConnection
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        sleep(rand(1,5));
        return $hdl->handle($rq);
    }
}