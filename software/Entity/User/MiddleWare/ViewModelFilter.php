<?php


namespace Software\Entity\User\MiddleWare;
use App\Constant\RequestModel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class ViewModelFilter
{
    public function __invoke(Request $rq, Handler $hdl)
    {
        $rq = $rq
            ->withAttribute(RequestModel::VIEW_MODEL_FILTER,[
            'Password','RolesMask','ForceLogout'])
            ->withAttribute(RequestModel::VIEW_MODEL_FILTER_TYPE,'out');
        return $hdl->handle($rq);
    }
}