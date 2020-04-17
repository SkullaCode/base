<?php


namespace App\MiddleWare\Simulation;


use App\Constant\RequestModel;
use App\Extension\Extensions;
use App\MiddleWare\BaseMiddleWareClass;
use Delight\Auth\Auth;
use Delight\Auth\AuthError;
use Delight\Auth\EmailNotVerifiedException;
use Delight\Auth\UnknownIdException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class GeneratedSession extends BaseMiddleWareClass
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
        if($this->Environment->SimulateLogin)
        {
            $session = $this->Utility->Session;
            $loadedSession = $session->GetItem(RequestModel::SESSION_LOAD);
            if(empty($loadedSession))
            {
                try
                {
                    $this->authenticator->admin()->logInAsUserById($this->Environment->SimulationID);
                }
                catch (UnknownIdException $e)
                {
                    $this->Redirect($rq);
                }
                catch (EmailNotVerifiedException $e)
                {
                    $this->Redirect($rq);
                }
                catch (AuthError $e)
                {
                    $this->Redirect($rq);
                }
                $loadedSession['hash'] =  mktime();
                $loadedSession[$this->Environment->AppUserID] = $this->authenticator->getUserId();
                $session->SetItem(RequestModel::SESSION_LOAD,$loadedSession);
            }

            $rq = $rq->withAddedHeader('X-Session-Token',md5($this->Utility->Request->UserAgent().$session->GetItem("hash")));
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