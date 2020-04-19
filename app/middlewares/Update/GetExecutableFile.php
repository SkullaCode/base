<?php


namespace App\MiddleWare\Update;

use App\Constant\RequestModel;
use App\Extension\Extensions;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class GetExecutableFile extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $dir = rtrim($vm->WorkArea,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        $file = @file_get_contents("{$dir}executor.json");
        if($file === false)
        {
            $rq = $rq
                ->withAttribute('Error_Location','GetExecutableFile')
                ->withAttribute('Error_Entity','Update')
                ->withAttribute('Error_Code','Execution file could not be loaded');
            return Extensions::ErrorHandler($rq);
        }
        $file = (DIRECTORY_SEPARATOR === '/')
            ? str_replace('||',DIRECTORY_SEPARATOR,$file)
            : str_replace('||','\\'.'\\',$file);

        $vm->Executor = (array)json_decode($file);
        $rq = $rq->withAttribute(RequestModel::VIEW_MODEL,$vm);
        return $hdl->handle($rq);
    }
}