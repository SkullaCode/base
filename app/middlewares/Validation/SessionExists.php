<?php


namespace App\MiddleWare\Validation;


use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class SessionExists extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Response $rs, $next)
    {
        $session = $this->Utility->Session;
        $crypt = md5($this->Utility->Request->UserAgent().$session->GetItem('Hash'));
        $token = $rq->getHeader('X-Session-Token');
        if(is_null($token))
            return $this->Redirect($rq,$rs);

        if(empty($token))
            return $this->Redirect($rq,$rs);

        if(is_array($token) && $token[0] !== $crypt)
            return $this->Redirect($rq,$rs);

        if(is_string($token) && $token !== $crypt)
            return $this->Redirect($rq,$rs);

        return $next($rq,$rs);
    }

    private function Redirect(Request $rq, Response $rs)
    {
        $this->Utility->Session->Destroy();
        $this->Utility->Session->Lock();
        if(strtolower($rq->getHeaderLine('X-Requested-With')) === 'xmlhttprequest')
        {
            return $rs->withStatus(403,"Access Forbidden");
        }
        $rs = $rs->withStatus(302)->withHeader('Location',$this->Utility->Request->BaseURL());
        return $rs;
    }
}