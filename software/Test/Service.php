<?php


namespace Software\Test;


use App\Controller\BaseController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Service extends BaseController
{
    public function ArrayFunctionUtility(ServerRequestInterface $rq, ResponseInterface $rs)
    {

    }

    public function CodeGeneratorUtility(ServerRequestInterface $rq, ResponseInterface $rs)
    {

    }

    public function ConfigurationUtility(ServerRequestInterface $rq, ResponseInterface $rs)
    {

    }

    public function DateTimeUtility(ServerRequestInterface $rq, ResponseInterface $rs)
    {

    }

    public function EmailUtility(ServerRequestInterface $rq, ResponseInterface $rs)
    {

    }

    public function FileSystemUtility(ServerRequestInterface $rq, ResponseInterface $rs)
    {

    }

    public function LoggerUtility(ServerRequestInterface $rq, ResponseInterface $rs)
    {

    }

    public function PasswordUtility(ServerRequestInterface $rq, ResponseInterface $rs)
    {

    }


    public function RequestUtility(ServerRequestInterface $rq, ResponseInterface $rs)
    {
        $var = $rs->getBody();
        $utility = [
            'BaseURL'       =>  $this->Utility->Request->BaseURL(),
            'UserAgent'     =>  $this->Utility->Request->UserAgent(),
            'HostName'      =>  $this->Utility->Request->HostName(),
            'IPAddress'     =>  $this->Utility->Request->IPAddress()
        ];
        $var->write(json_encode($utility,JSON_PRETTY_PRINT));
        return $rs->withBody($var);
    }

    public function SessionUtility(ServerRequestInterface $rq, ResponseInterface $rs)
    {

    }
}