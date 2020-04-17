<?php


namespace Software\Settings;


use App\Constant\RequestModel;
use App\Controller\BaseController;
use App\Extension\Extensions;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Service extends BaseController
{
    public function Save(Request $rq, Response $rs)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $model = $vm->SettingsModel;
        $this->Utility->Setting->Save($model);
        return Extensions::SuccessHandler($rq,"Settings updated");
    }

    public function Load(Request $rq, Response $rs)
    {
        $model = $this->Utility->Setting->Load();
        $rq = $rq->withAttribute(RequestModel::PROCESSED_MODEL,$model);
        return Extensions::SuccessHandler($rq);
    }
}