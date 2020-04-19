<?php


namespace Software\Entity\User\MiddleWare;
use App\Constant\ErrorCode;
use App\Constant\ErrorModel;
use App\Constant\RequestModel;
use App\Extension\Extensions;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Software\EntityContext\File;

class NormalizeProfilePicture extends BaseMiddleWareClass
{
    /**
     * @var File
     */
    private $file_context;

    public function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
        $this->file_context = new File($c);
    }

    public function __invoke(Request $rq, Handler $hdl)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $file = $rq->getUploadedFiles();
        if(!isset($file['picture']))
        {
            $rq = $rq
                ->withAttribute(ErrorModel::ERROR_LOCATION,"User")
                ->withAttribute(ErrorModel::ERROR_ENTITY,"NormalizeProfilePicture")
                ->withAttribute(ErrorModel::ERROR_CODE,ErrorCode::REQUIRED_FIELD_MISSING);
            return Extensions::ErrorHandler($rq);
        }
        $vm->ProfilePicture = $file['picture'];
        $vm->ProfilePictureFileName = "profile_picture:user_id={$vm->User->ID}";
        $vm->File = $this->file_context->Get($vm->ProfilePictureFileName);
        return $hdl->handle($rq);
    }
}