<?php


namespace App\MiddleWare\Update;


use App\Constant\RequestModel;
use App\Extension\Extensions;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class UploadUpdatePackage extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $uploadedFile = $rq->getUploadedFiles();
        if(!isset($uploadedFile['update']))
        {
            $rq = $rq
                ->withAttribute('Error_Location','UploadUpdatePackage')
                ->withAttribute('Error_Entity','Update')
                ->withAttribute('Error_Code','Uploaded update was not found');
            return Extensions::ErrorHandler($rq);
        }
        /**
         * @var UploadedFileInterface $uploadedFile
         */
        $uploadedFile = $uploadedFile['update'];
        $fileName = $uploadedFile->getClientFilename();
        $x = explode('.',$fileName);
        $ext = (isset($x[1])) ? $x[1] : null;
        if(is_null($ext))
        {
            $rq = $rq
                ->withAttribute('Error_Location','UploadUpdatePackage')
                ->withAttribute('Error_Entity','Update')
                ->withAttribute('Error_Code','Could not determine file extension');
            return Extensions::ErrorHandler($rq);
        }
        $vm->UpdateArchive = $vm->WorkArea.DIRECTORY_SEPARATOR."UpdatePackage.zip";
        $uploadedFile->moveTo($vm->UpdateArchive);
        $rq = $rq->withAttribute(RequestModel::VIEW_MODEL,$vm);
        return $hdl->handle($rq);
    }
}