<?php


namespace App\MiddleWare\Update;


use App\Constant\ErrorModel;
use App\Constant\RequestModel;
use App\Extension\Extensions;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class LoadFileSystemLocations extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $session = $this->Utility->Session;
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $vm->WorkArea = $session->GetItem('WorkArea');
        if(is_null($vm->WorkArea))
        {
            $rq = $rq
                ->withAttribute(ErrorModel::ERROR_LOCATION,"Update")
                ->withAttribute(ErrorModel::ERROR_ENTITY,"CanExtract")
                ->withAttribute(ErrorModel::ERROR_CODE,"Working directory does not exist");
            return Extensions::ErrorHandler($rq);
        }
        $vm->UpdateSource = $session->GetItem('UpdateSource');
        if(is_null($vm->UpdateSource))
        {
            $rq = $rq
                ->withAttribute(ErrorModel::ERROR_LOCATION,"Update")
                ->withAttribute(ErrorModel::ERROR_ENTITY,"CanExtract")
                ->withAttribute(ErrorModel::ERROR_CODE,"Working directory does not exist");
            return Extensions::ErrorHandler($rq);
        }
        $vm->UpdateArchive = $session->GetItem('UpdateArchive');
        if(is_null($vm->UpdateArchive))
        {
            $rq = $rq
                ->withAttribute(ErrorModel::ERROR_LOCATION,"Update")
                ->withAttribute(ErrorModel::ERROR_ENTITY,"CanExtract")
                ->withAttribute(ErrorModel::ERROR_CODE,"Working directory does not exist");
            return Extensions::ErrorHandler($rq);
        }
        $rq = $rq->withAttribute(RequestModel::VIEW_MODEL,$vm);
        return $hdl->handle($rq);
    }
}