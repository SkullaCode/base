<?php


namespace App\MiddleWare\Transformation;


use App\Constant\RequestModel;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class SessionTracker extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Response $rs, $next)
    {
        $session = $this->Utility->Session;
        $sessionLoad = $session->GetItem(RequestModel::SESSION_LOAD,[]);
        if(empty($sessionLoad))
        {
            $sessionLoad = [
                'token'     =>  null,
                'instance'  =>  'new',
                'params'    =>  []
            ];
            $session->SetItem(RequestModel::SESSION_LOAD,$sessionLoad);
        }
        $rq = $rq
            ->withAttribute(RequestModel::SESSION_TOKEN,$sessionLoad['token'])
            ->withAttribute(RequestModel::APPLICATION_STATE,$sessionLoad['instance']);

        $rs =  $next($rq,$rs);
        $this->Utility->Session->Lock();
        return $rs;
    }
}