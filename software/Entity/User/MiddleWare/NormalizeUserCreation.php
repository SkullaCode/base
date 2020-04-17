<?php


namespace Software\Entity\User\MiddleWare;
use App\Constant\RequestModel;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;


class NormalizeUserCreation extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $defaultPassword = $this->Environment->DefaultPassword;
        if(strtolower($defaultPassword) === 'random')
            $defaultPassword = $this->Utility->CodeGenerator->AlphaNumericString(8);
        $vm->Password = $defaultPassword;
        $vm->Email = $vm->email;
        $vm->Username = $vm->username;
        $rq = $rq->withAttribute(RequestModel::VIEW_MODEL,$vm);
        return $hdl->handle($rq);
    }
}