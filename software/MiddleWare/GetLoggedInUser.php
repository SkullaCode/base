<?php


namespace Software\MiddleWare;


use App\Constant\RequestModel;
use App\Extension\Extensions;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class GetLoggedInUser extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $session = $this->Utility->Session->GetItem(RequestModel::SESSION_LOAD);
        if(!isset($session[$this->Environment->AppUserID]))
        {
            $rq = $rq
                ->withAttribute('Error_Location','GetLoggedInUser')
                ->withAttribute('Error_Entity','Tag')
                ->withAttribute('Error_Code','User not found');
            return Extensions::ErrorHandler($rq);
        }
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $vm->UID = $session[$this->Environment->AppUserID];
        $rq = $rq->withAttribute(RequestModel::VIEW_MODEL,$vm);
        return $hdl->handle($rq);
    }
}