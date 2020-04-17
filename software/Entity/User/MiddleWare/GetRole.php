<?php


namespace Software\Entity\User\MiddleWare;


use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class GetRole extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        return $hdl->handle($rq);
    }
}