<?php


namespace App\MiddleWare\Transformation;


use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class SessionTracker extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Response $rs, $next)
    {
        $session = $this->Utility->Session;
        $sessionLoad = $session->GetItem("SESSION_LOAD",[]);
        if(empty($sessionLoad))
        {
            $sessionLoad = [
                'token'     =>  null,
                'instance'  =>  'new',
                'params'    =>  []
            ];
            $session->SetItem("SESSION_LOAD",$sessionLoad);
        }
        $rq = $rq
            ->withAttribute('SessionToken',$sessionLoad['token'])
            ->withAttribute('ApplicationState',$sessionLoad['instance']);

        $rs =  $next($rq,$rs);
        $this->Utility->Session->Lock();
        return $rs;
    }
}