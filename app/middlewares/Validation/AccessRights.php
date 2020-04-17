<?php


namespace App\MiddleWare\Validation;


use App\Constant\ErrorModel;
use App\Constant\RequestModel;
use App\Extension\Extensions;
use App\MiddleWare\BaseMiddleWareClass;
use Delight\Auth\Auth;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class AccessRights extends BaseMiddleWareClass
{
    /**
     * @var Auth
     */
    private $auth;

    public function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
        $this->auth = $c->get("Authenticator");
    }

    public function __invoke(Request $rq, Handler $hdl)
    {
        if($this->Environment->AuthenticationOn)
        {
            if($this->Environment->AccessControlOn)
            {
                $stage1 = $this->GetRoutePrivileges($rq,RequestModel::ACCESS_RIGHTS_CONTROLLER);
                if(!empty($stage1))
                {
                    $legit = false;
                    foreach($stage1 as $item)
                    {
                        if(!$this->auth->hasRole($item)) continue;
                        $legit = true;
                        break;
                    }
                    if(!$legit)
                    {
                        $rq = $rq
                            ->withAttribute(ErrorModel::ERROR_LOCATION,'MiddleWare')
                            ->withAttribute(ErrorModel::ERROR_ENTITY,'AccessRights')
                            ->withAttribute(ErrorModel::ERROR_CODE,'Access denied. User does not have required credentials');
                        return Extensions::ErrorHandler($rq);
                    }
                    $stage2 = $this->GetRoutePrivileges($rq,RequestModel::ACCESS_RIGHTS_METHOD);
                    if(!empty($stage2))
                    {
                        $legit = false;
                        foreach($stage2 as $item)
                        {
                            if(!$this->auth->hasRole($item)) continue;
                            $legit = true;
                            break;
                        }
                        if(!$legit)
                        {
                            $rq = $rq
                                ->withAttribute(ErrorModel::ERROR_LOCATION,'MiddleWare')
                                ->withAttribute(ErrorModel::ERROR_ENTITY,'AccessRights')
                                ->withAttribute(ErrorModel::ERROR_CODE,'Access denied. User does not have required credentials');
                            return Extensions::ErrorHandler($rq);
                        }
                    }
                }
                else
                {
                    $stage2 = $this->GetRoutePrivileges($rq,RequestModel::ACCESS_RIGHTS_METHOD);
                    if(!empty($stage2))
                    {
                        $legit = false;
                        foreach($stage2 as $item)
                        {
                            if(!$this->auth->hasRole($item)) continue;
                            $legit = true;
                            break;
                        }
                        if(!$legit)
                        {
                            $rq = $rq
                                ->withAttribute(ErrorModel::ERROR_LOCATION,'MiddleWare')
                                ->withAttribute(ErrorModel::ERROR_ENTITY,'AccessRights')
                                ->withAttribute(ErrorModel::ERROR_CODE,'Access denied. User does not have required credentials');
                            return Extensions::ErrorHandler($rq);
                        }
                    }
                }
            }
        }
        return $hdl->handle($rq);
    }

    private function GetRoutePrivileges(Request $rq,$stage)
    {
        return $rq->getAttribute($stage,[]);
    }
}