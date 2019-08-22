<?php


namespace App\Controller;

use App\Constant\RequestModel;
use App\Extension\Extensions;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class AppController extends BaseController
{
    public function Load(Request $rq, Response $rs)
    {
        $this->Message = "Landing Page";
        $params = (object)$rq->getQueryParams();
        $params->title = "Landing Page";
        $params->powered = "Powered by {$this->Environment->RenderEngine}";
        $rq = $rq
            ->withAttribute(RequestModel::PROCESSED_MODEL,$params)
            ->withAttribute(RequestModel::TEMPLATE,"landing.index");
        return Extensions::SuccessHandler($rq,$rs,$this->Message);
    }

    public function Kill(Request $rq, Response $rs)
    {
        $session = $this->Utility->Session;
        $session->Destroy();
        $rs = $rs->withHeader('Location','/');
        return Extensions::SuccessHandler($rq,$rs,302);
    }

    public function Bootstrap(Request $rq, Response $rs)
    {

    }
}