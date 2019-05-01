<?php


namespace App\MiddleWare\Validation;


use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class IsCron extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Response $rs, $next)
    {
        return $next($rq,$rs);
    }
}