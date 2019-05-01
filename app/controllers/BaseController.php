<?php


namespace App\Controller;


use App\Provider\ContextProvider;
use App\Provider\UtilityProvider;
use App\Traits\ErrorResultResponse;
use App\Traits\ViewModelAutoMap;
use App\Traits\ViewModelPopulate;
use Psr\Http\Message\StreamInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class BaseController
{
    /**
     * @var UtilityProvider
     */
    protected $Utility;

    /**
     * @var ContextProvider
     */
    protected $Context;

    /**
     * @var array
     */
    protected $Settings;

    /**
     * @var array
     */
    protected $Environment;

    /**
     * @var string
     */
    protected $Message;

    public function __construct(ContainerInterface $c)
    {
        $this->Utility = $c->get("UtilityProvider");
        $this->Context = $c->get("ContextProvider");
        $this->Settings = $c->get('settings');
        $this->Environment = $this->Settings['config'];
        $this->Message = "";
    }

    protected function Redirect(Request $rq, Response $rs)
    {
        if($rq->getHeaderLine('X-Requested-With') === 'XMLHttpRequest')
        {
            $rs = $rs->withStatus(301);
            return $rs;
        }
        $rs = $rs->withStatus(301)->withHeader('Location',$this->Utility->Request->BaseURL());
        return $rs;
    }

    use ViewModelPopulate, ErrorResultResponse, ViewModelAutoMap;
}