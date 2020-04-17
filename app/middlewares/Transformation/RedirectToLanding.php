<?php


namespace App\MiddleWare\Transformation;


use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class RedirectToLanding extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $request = $this->Utility->Request;
        $response = $hdl->handle($rq);
        $response = $response
            ->withStatus(302)
            ->withHeader('Location',(string)$request->BaseURL())
            ->withHeader('X-Redirect-To',$request->BaseURL());
        return $response;
    }
}