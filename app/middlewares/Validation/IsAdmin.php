<?php


namespace App\MiddleWare\Validation;


use App\Constant\ErrorCode;
use App\Extension\Extensions;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class IsAdmin extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Response $rs, $next)
    {
        if(!$rq->getAttribute('IsAdmin'))
        {
            $rq = $rq
                ->withAttribute("Error_Location","Validation.Auth")
                ->withAttribute("Error_Entity","User Account")
                ->withAttribute("Error_Code",ErrorCode::ACCOUNT_NOT_ADMIN);
            return Extensions::ErrorHandler($rq,$rs);
        }

        return $next($rq,$rs);
    }
}