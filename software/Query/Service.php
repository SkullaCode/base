<?php

namespace Software\Query;


use App\Controller\BaseController;
use App\Extension\Extensions;
use Slim\Http\Request;
use Slim\Http\Response;

class Service extends BaseController
{
    public function Brand(Request $rq, Response $rs)
    {
        $model = $this->Context->Brand->Get($rq->getAttribute("id"));
        $rq = $rq->withAttribute("ProcessedViewModel",$model);

        return (!is_null($model))
            ? Extensions::SuccessHandler($rq,$rs,"Brand found")
            : Extensions::ErrorHandler($rq,$rs,500,"Brand not found");
    }

    public function Decoration(Request $rq, Response $rs)
    {
        $model = $this->Context->Decoration->Get($rq->getAttribute("id"));
        $rq = $rq->withAttribute("ProcessedViewModel",$model);
        return Extensions::SuccessHandler($rq,$rs,"Decoration found");
    }

    public function GiftContainer(Request $rq, Response $rs)
    {
        $model = $this->Context->GiftContainer->Get($rq->getAttribute("id"));
        $rq = $rq->withAttribute("ProcessedViewModel",$model);
        return Extensions::SuccessHandler($rq,$rs,"GiftContainer found");
    }

    public function GiftItem(Request $rq, Response $rs)
    {
        $model = $this->Context->GiftItem->Get($rq->getAttribute("id"));
        $rq = $rq->withAttribute("ProcessedViewModel",$model);
        return Extensions::SuccessHandler($rq,$rs,"GiftItem found");
    }

    public function Location(Request $rq, Response $rs)
    {
        $model = $this->Context->Location->Get($rq->getAttribute("id"));
        $rq = $rq->withAttribute("ProcessedViewModel",$model);
        return Extensions::SuccessHandler($rq,$rs,"Location found");
    }

    public function Package(Request $rq, Response $rs)
    {
        $model = $this->Context->Package->Get($rq->getAttribute("id"));
        $rq = $rq->withAttribute("ProcessedViewModel",$model);
        return Extensions::SuccessHandler($rq,$rs,"Package found");
    }

    public function Purchase(Request $rq, Response $rs)
    {
        $model = $this->Context->Purchase->Get($rq->getAttribute("id"));
        $rq = $rq->withAttribute("ProcessedViewModel",$model);
        return Extensions::SuccessHandler($rq,$rs,"Purchase found");
    }
}