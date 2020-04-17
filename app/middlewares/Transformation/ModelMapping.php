<?php


namespace App\MiddleWare\Transformation;


use App\Constant\RequestModel;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class ModelMapping extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $method = strtolower($rq->getMethod());
        $rq = $rq->withAttribute(RequestModel::MODEL_MAPPING,$method);
        /*if($method === 'put') $rq = $rq->withAttribute('ModelMapping',\App\Constant\ModelMapping::ADDING);
        elseif($method === 'post') $rq = $rq->withAttribute('ModelMapping',\App\Constant\ModelMapping::UPDATING);
        elseif($method === 'delete') $rq = $rq->withAttribute('ModelMapping',\App\Constant\ModelMapping::DELETING);*/
        return $hdl->handle($rq);
    }
}