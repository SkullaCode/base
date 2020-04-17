<?php


namespace App\MiddleWare\Transformation;

use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;


class ResponseHeader extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        if($this->Environment->Mode === "development")
        {
            //todo figure out how to set cors headers
            /*$rs = $rs
                ->withHeader('Access-Control-Allow-Origin','*')
                ->withHeader('Access-Control-Allow-Headers','X-Requested-With, Content-Type');*/
        }
        return $hdl->handle($rq);
    }
}