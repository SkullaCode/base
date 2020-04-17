<?php


namespace App\Controller;

use App\Provider\Utility;
use App\Traits\ViewModelAutoMap;
use App\Traits\ViewModelPopulate;
use App\Utility\Configuration;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use ReflectionClass;
use ReflectionException;
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

    /**
     * @var ContainerInterface
     */
    protected $Container;

    public function __construct(ContainerInterface $c)
    {
        $this->Utility = $c->get("UtilityProvider");
        $this->Context = $c->get("ContextProvider");
        $this->Environment = $c->get('ConfigurationUtility');
        $this->Message = "";
        $this->Container = $c;
    }

    protected function Redirect(Request $rq, Response $rs)
    {
        if(strtolower($rq->getHeaderLine('X-Requested-With')) === 'xmlhttprequest')
        {
            $rs = $rs->withStatus(301);
            return $rs;
        }
        $rs = $rs->withStatus(301)->withHeader('Location',$this->Utility->Request->BaseURL());
        return $rs;
    }

    protected function FireHook($hook, Request &$rq)
    {
        $class = $rq->getAttribute($hook);
        if(!is_null($class))
        {
            try {
                $class = new ReflectionClass($class);
                $class->newInstance($this->Container)($rq);
            } catch (ReflectionException $e) {
            }
        }
    }

    use ViewModelPopulate, ViewModelAutoMap;
}