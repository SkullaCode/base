<?php


namespace App\MiddleWare\Update;


use App\Constant\RequestModel;
use App\Extension\Extensions;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use ZipArchive;


class ExtractUpdatePackage extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $zip = new ZipArchive();
        $res = $zip->open($vm->UpdateArchive);
        if($res === false)
        {
            $rq = $rq
                ->withAttribute('Error_Location','ExtractUpdatePackage')
                ->withAttribute('Error_Entity','Update')
                ->withAttribute('Error_Code','Archive was not extracted');
            return Extensions::ErrorHandler($rq);
        }
        $zip->extractTo($vm->WorkArea);
        $zip->close();
        return $hdl->handle($rq);
    }
}