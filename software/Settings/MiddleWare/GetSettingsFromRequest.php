<?php


namespace Software\Settings\MiddleWare;


use App\Constant\RequestModel;
use App\MiddleWare\BaseMiddleWareClass;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionObject;
use ReflectionProperty;
use Software\Settings\Model;

class GetSettingsFromRequest extends BaseMiddleWareClass
{
    public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $hdl)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $model = new Model();
        $vars = (new ReflectionObject($model))->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach($vars as $var)
            $model->{$var->getName()} = $vm->{$var->getName()};

        $vm->SettingsModel = $model;
        $rq = $rq->withAttribute(RequestModel::VIEW_MODEL,$vm);
        return $hdl->handle($rq);
    }
}