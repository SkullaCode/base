<?php


namespace App\MiddleWare\Validation;


use App\Constant\RequestModel;
use App\Extension\Extensions;
use App\MiddleWare\BaseMiddleWareClass;
use Delight\Auth\Auth;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;


class SessionExists extends BaseMiddleWareClass
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var Auth
     */
    private $authenticator;

    public function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
        $this->response = $c->get("Response");
        $this->authenticator = $c->get("Authenticator");
    }

    public function __invoke(Request $rq, Handler $hdl)
    {
        if($this->Environment->AuthenticationOn)
        {
            $session = $this->Utility->Session;
            $loadedSession = $session->GetItem(RequestModel::SESSION_LOAD);
            if(!$this->authenticator->isLoggedIn()) return $this->Redirect($rq);
            if(strtolower($rq->getHeaderLine('X-Requested-With')) === 'xmlhttprequest')
            {
                $crypt = md5($this->Utility->Request->UserAgent().$session->GetItem('hash'));
                $token = $rq->getHeader('X-Session-Token');
                if(is_null($token))
                    return $this->Redirect($rq);

                if(empty($token))
                    return $this->Redirect($rq);

                if(is_array($token) && $token[0] !== $crypt)
                    return $this->Redirect($rq);

                if(is_string($token) && $token !== $crypt)
                    return $this->Redirect($rq);
            }
            if(empty($loadedSession)) return $this->Redirect($rq);
            if($this->authenticator->isBanned()) return Extensions::ErrorHandler($rq,"Your account is banned.");
            if($this->authenticator->isLocked()) return Extensions::ErrorHandler($rq,"Your account is locked.");
        }
        return $hdl->handle($rq);
    }

    private function Redirect(Request $rq)
    {
        $this->Utility->Session->Destroy();
        $this->Utility->Session->Lock();
        $location = rtrim($this->Utility->Request->BaseURL(),'/').'/';
        $location = "{$location}login";
        if(strtolower($rq->getHeaderLine('X-Requested-With')) === 'xmlhttprequest')
        {
            return Extensions::RedirectHandler($rq,$location);
        }
        $this->response = $this->response->withStatus(302)->withHeader('Location',$location);
        return $this->response;
    }
}