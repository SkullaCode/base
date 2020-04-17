<?php


namespace App\MiddleWare\Transformation;


use App\Constant\RequestModel;
use App\MiddleWare\BaseMiddleWareClass;
use App\ViewModel\ViewModel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;


class PopulateViewModel extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, RequestHandlerInterface $hdl)
    {
        $vm = new ViewModel($this->Scrape($rq));
        $rq = $rq->withAttribute(RequestModel::VIEW_MODEL,$vm);
        return $hdl->handle($rq);
    }
}