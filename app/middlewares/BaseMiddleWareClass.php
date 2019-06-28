<?php


namespace App\MiddleWare;

use App\Provider\Utility;
use App\Traits\ViewModelPopulate;
use App\Utility\Configuration;
use Psr\Container\ContainerInterface;
use Software\Provider\Context;

class BaseMiddleWareClass
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

    public function __construct(ContainerInterface $c)
    {
        $this->Utility = $c->get("UtilityProvider");
        $this->Context = $c->get("ContextProvider");
        $this->Environment = $c->get('ConfigurationUtility');
    }

    use ViewModelPopulate;
}