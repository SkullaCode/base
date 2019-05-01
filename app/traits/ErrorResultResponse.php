<?php


namespace App\Traits;


use App\Extension\Extensions;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


trait ErrorResultResponse
{
    protected function ReturnResult($code, $entity, Request $rq, Response $rs)
    {
        $rq = $rq
            ->withAttribute("Error_Location","Validation")
            ->withAttribute("Error_Entity",$entity)
            ->withAttribute("Error_Code",$code);
        return Extensions::ErrorHandler($rq,$rs);
    }
}