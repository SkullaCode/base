<?php


namespace Software\Entity\User\MiddleWare;
use App\Constant\RequestModel;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;


class ValidateRegistration extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $vm->Email = $vm->email;
        $vm->Password = $vm->password;
        $vm->Username = $vm->username;
        $rq = $rq->withAttribute(RequestModel::VIEW_MODEL,$vm);
        return $hdl->handle($rq);
    }
}