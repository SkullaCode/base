<?php


namespace App\Controller;

use App\Provider\Utility;
use App\Traits\ViewModelAutoMap;
use App\Traits\ViewModelPopulate;
use App\Utility\Configuration;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Software\Provider\Context;

class BaseController
{
    /**
     * @var Utility
     */
    protected $Utility;

    /**
     * @var Context
     */
    protected $Context;

    /**
     * @var Configuration
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
        $this->Environment = $c->get('ConfigurationUtility');
        $this->Message = "";
    }

    protected function Redirect(Request $rq, Response $rs)
    {
        //if($rq->getHeaderLine('X-Requested-With') === 'XMLHttpRequest')
        if(strtolower($rq->getHeaderLine('X-Requested-With')) === 'xmlhttprequest')
        {
            $rs = $rs->withStatus(301);
            return $rs;
        }
        $rs = $rs->withStatus(301)->withHeader('Location',$this->Utility->Request->BaseURL());
        return $rs;
    }

    use ViewModelPopulate, ViewModelAutoMap;
}