<?php


namespace App\MiddleWare\Transformation;

use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class ResponseHeader extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Response $rs, $next)
    {
        if($this->Environment->Mode === "development")
        {
            $rs = $rs
                ->withHeader('Access-Control-Allow-Origin','*')
                ->withHeader('Access-Control-Allow-Headers','X-Requested-With, Content-Type');
        }
        return $next($rq,$rs);
    }
}