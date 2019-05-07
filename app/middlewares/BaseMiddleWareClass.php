<?php


namespace App\MiddleWare;

use App\Traits\ViewModelPopulate;
use Psr\Container\ContainerInterface;
use Software\Provider\Context;
use Software\Provider\Utility;

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
     * @var array
     */
    protected $Environment;

    public function __construct(ContainerInterface $c)
    {
        $this->Utility = $c->get("UtilityProvider");
        $this->Context = $c->get("ContextProvider");
        $this->Environment = $c->get('settings')['config'];
    }

    use ViewModelPopulate;
}