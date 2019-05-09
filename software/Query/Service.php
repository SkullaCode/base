<?php

namespace Software\Query;


use App\Controller\BaseController;
use App\Extension\Extensions;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Service extends BaseController
{
    public function File(Request $rq, Response $rs)
    {
        $model = $this->Context->File->Get($rq->getAttribute("id"));
        $rq = $rq->withAttribute("ProcessedViewModel",$model);

        return (!is_null($model))
            ? Extensions::SuccessHandler($rq,$rs,"File found")
            : Extensions::ErrorHandler($rq,$rs,500,"File not found");
    }
}