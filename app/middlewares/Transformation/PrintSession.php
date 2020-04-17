<?php


namespace App\MiddleWare\Transformation;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class PrintSession
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $response = $hdl->handle($rq);
        $body = $response->getBody();
        $body->write(json_encode($_SESSION,JSON_PRETTY_PRINT));
        $response = $response->withBody($body);
        return $response;
    }
}