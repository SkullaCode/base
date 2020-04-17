<?php


namespace App\MiddleWare\Update;

use App\Constant\RequestModel;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class WorkAreaSetup extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $root_dir = rtrim($this->Environment->StorageDirectory,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        $update_dir = "{$root_dir}UpdateProcessing";
        if(!file_exists($update_dir))
            mkdir($update_dir);
        $this->Utility->FileSystem->CleanDir($update_dir);
        $vm->WorkArea = $update_dir;
        $vm->UpdateSource = $update_dir.DIRECTORY_SEPARATOR."Updates".DIRECTORY_SEPARATOR;
        $rq = $rq->withAttribute(RequestModel::VIEW_MODEL,$vm);
        $response = $hdl->handle($rq);
        $this->Utility->FileSystem->CleanDir($update_dir);
        return $response;
    }
}