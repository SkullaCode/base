<?php


namespace App\MiddleWare\Transformation;


use App\Constant\RequestModel;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ModelMapping extends BaseMiddleWareClass
{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, $next)
    {
        $method = strtolower($rq->getMethod());
        $rq = $rq->withAttribute(RequestModel::MODEL_MAPPING,$method);
        /*if($method === 'put') $rq = $rq->withAttribute('ModelMapping',\App\Constant\ModelMapping::ADDING);
        elseif($method === 'post') $rq = $rq->withAttribute('ModelMapping',\App\Constant\ModelMapping::UPDATING);
        elseif($method === 'delete') $rq = $rq->withAttribute('ModelMapping',\App\Constant\ModelMapping::DELETING);*/
        return $next($rq,$rs);
    }
}