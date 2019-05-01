<?php


namespace App\MiddleWare\Transformation;


use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class PopulateViewModel extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Response $rs, $next)
    {
        $rq = $rq->withAttribute("ViewModel",$this->Scrape($rq));
        return $next($rq,$rs);
    }
}