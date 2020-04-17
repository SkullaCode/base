<?php


namespace Software\Entity\User\MiddleWare;
use App\Constant\ErrorCode;
use App\Constant\ErrorModel;
use App\Constant\RequestModel;
use App\Extension\Extensions;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class LoadUserFromId extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $user = $this->Context->User->Get($vm->id);
        if(is_null($user))
        {
            $rq = $rq
                ->withAttribute(ErrorModel::ERROR_LOCATION,"User")
                ->withAttribute(ErrorModel::ERROR_ENTITY,"LoadUserFromId")
                ->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::NOT_FOUND);
            return Extensions::ErrorHandler($rq);
        }
        $vm->User = $user;
        $rq = $rq->withAttribute(RequestModel::VIEW_MODEL,$vm);
        return $hdl->handle($rq);
    }
}