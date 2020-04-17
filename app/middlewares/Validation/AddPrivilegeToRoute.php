<?php


namespace App\MiddleWare\Validation;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;


class AddPrivilegeToRoute
{
    private $access;
    private $privileges;

    public function __construct($accessRight, $privileges)
    {
        $this->access = $accessRight;
        $this->privileges = $privileges;
    }

    public function __invoke(Request $rq, Handler $hdl)
    {
        $rq = $rq->withAttribute($this->access,$this->privileges);
        return $hdl->handle($rq);
    }
}