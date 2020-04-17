<?php


namespace Software\Entity\User\MiddleWare;


use App\Constant\RequestModel;
use App\Extension\Extensions;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class GetUploadedProfilePicture extends BaseMiddleWareClass
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $uploadedFile = $rq->getUploadedFiles();
        if(!isset($uploadedFile['ProfilePicture']))
        {
            $rq = $rq
                ->withAttribute('Error_Location','GetUploadedProfilePicture')
                ->withAttribute('Error_Entity','User')
                ->withAttribute('Error_Code','Uploaded profile picture was not found');
            return Extensions::ErrorHandler($rq);
        }
        $vm->ProfilePicture = $uploadedFile['ProfilePicture'];
        $rq = $rq->withAttribute(RequestModel::VIEW_MODEL,$vm);
        return $hdl->handle($rq);
    }
}