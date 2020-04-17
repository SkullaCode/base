<?php


namespace App\MiddleWare\Transformation;


use App\Constant\RequestModel;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;


class SessionTracker extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $session = $this->Utility->Session;
        $sessionLoad = $session->GetItem(RequestModel::SESSION_LOAD);
        if(is_null($sessionLoad)) $session->SetItem(RequestModel::SESSION_LOAD,[]);
        $rs =  $hdl->handle($rq);
        $this->Utility->Session->Lock();
        return $rs;
    }
}