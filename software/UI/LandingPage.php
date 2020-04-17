<?php


namespace Software\UI;


use App\Constant\ErrorCode;
use App\Constant\ErrorModel;
use App\Constant\RequestModel;
use App\Controller\BaseController;
use App\Extension\Extensions;
use Delight\Auth\AuthError;
use Delight\Auth\InvalidSelectorTokenPairException;
use Delight\Auth\TokenExpiredException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UserAlreadyExistsException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class LandingPage extends BaseController
{
    public function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
        $this->Message = 'Landing Page';
    }

    /**
     * @param string $elem
     * @param object $params
     * @param Request $rq
     */
    private function BaseParameters($elem,$params,&$rq)
    {
        $session                    = $this->Utility->Session->GetItem(RequestModel::SESSION_LOAD);
        $el                         = $this->Utility->StringFunction->CapitalizeFirstLetter($elem);
        $pl                         = $this->Utility->StringFunction->Pluralize($elem);
        $title                      = $this->Utility->StringFunction->Pluralize($el);
        $params->title              = $title;
        $params->sub_title          = "Manage {$title}";
        $params->powered            = "Powered by {$this->Environment->RenderEngine}";
        $params->base_url           = $this->Utility->Request->BaseURL();
        $params->app_name           = "Event Viewer Application";
        $params->addButtonHeader    = "Add {$el}";
        $params->tableHeader        = "{$el} Listing";
        $params->user               = (object)["name" => $session['user']['user_name'], "email" => $session['user']['email']];
        $rq = $rq
            ->withAttribute(RequestModel::PROCESSED_MODEL,$params)
            ->withAttribute(RequestModel::TEMPLATE,"app.{$pl}.panel");
    }

    public function Login(Request $rq)
    {
        $params                     = (object)$rq->getQueryParams();
        $params->title              = "Login";
        $params->base_url           = $this->Utility->Request->BaseURL();
        $params->app_name           = "Event Viewer Application";
        $params->default_panel      = "login";
        $rq = $rq
            ->withAttribute(RequestModel::PROCESSED_MODEL,$params)
            ->withAttribute(RequestModel::TEMPLATE,"landing.login")
            ->withAttribute(RequestModel::RENDER_ENGINE,"php");
        return Extensions::SuccessHandler($rq);
    }

    public function Logout(Request $rq)
    {
        $session = $this->Utility->Session;
        $session->Destroy();
        return Extensions::RedirectHandler($rq,$this->Utility->Request->BaseURL('login'));
    }

    public function Index(Request $rq)
    {
        $params                     = (object)$rq->getQueryParams();
        $params->name               = $this->Environment->CompanyConfig['name'];
        $params->powered            = "PHP Render Engine";
        $rq = $rq
            ->withAttribute(RequestModel::PROCESSED_MODEL,$params)
            ->withAttribute(RequestModel::TEMPLATE,"landing.index")
            ->withAttribute(RequestModel::RENDER_ENGINE,"php");
        return Extensions::SuccessHandler($rq);
    }

    public function ChangePassword(Request $rq)
    {
        $session                    = $this->Utility->Session;
        $params                     = (object)$rq->getQueryParams();
        $params->title              = "Change Password";
        $params->base_url           = $this->Utility->Request->BaseURL();
        $params->app_name           = "Event Viewer Application";
        $params->default_panel      = "change-password";
        $params->selector           = $session->GetFlashItem("selector");
        $params->token              = $session->GetFlashItem("token");
        $rq = $rq
            ->withAttribute(RequestModel::PROCESSED_MODEL,$params)
            ->withAttribute(RequestModel::TEMPLATE,"landing.login")
            ->withAttribute(RequestModel::RENDER_ENGINE,"php");
        return Extensions::SuccessHandler($rq);
    }

    public function Profile(Request $rq)
    {
        $session                    = $this->Utility->Session->GetItem(RequestModel::SESSION_LOAD);
        $params                     = (object)$rq->getQueryParams();
        $params->title              = "Profile";
        $params->sub_title          = "Change your profile settings";
        $params->base_url           = $this->Utility->Request->BaseURL();
        $params->username           = $session['user']['user_name'];
        $params->email              = $session['user']['email'];
        $params->app_name           = "Event Viewer Application";
        $params->user               = (object)["name" => $session['user']['user_name'], "email" => $session['user']['email']];
        $rq = $rq
            ->withAttribute(RequestModel::PROCESSED_MODEL,$params)
            ->withAttribute(RequestModel::TEMPLATE,"landing.profile");
        return Extensions::SuccessHandler($rq,$this->Message);
    }

    public function Dashboard(Request $rq)
    {
        $session                    = $this->Utility->Session->GetItem(RequestModel::SESSION_LOAD);
        $params                     = (object)$rq->getQueryParams();
        $params->title              = "Dashboard";
        $params->sub_title          = "System overview and metrics";
        $params->base_url           = $this->Utility->Request->BaseURL();
        $params->app_name           = "Event Viewer Application";
        $params->user               = (object)["name" => $session['user']['user_name'], "email" => $session['user']['email']];
        $rq = $rq
            ->withAttribute(RequestModel::PROCESSED_MODEL,$params)
            ->withAttribute(RequestModel::TEMPLATE,"landing.panel");
        return Extensions::SuccessHandler($rq,$this->Message);
    }

    public function User(Request $rq)
    {
        $params = (object)$rq->getQueryParams();
        $this->BaseParameters('user',$params,$rq);
        return Extensions::SuccessHandler($rq,$this->Message);
    }
}