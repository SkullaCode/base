<?php


namespace App\MiddleWare;


use App\Provider\ContextProvider;
use App\Provider\UtilityProvider;
use App\Traits\ErrorResultResponse;
use App\Traits\ViewModelPopulate;
use Psr\Container\ContainerInterface;

class BaseMiddleWareClass
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

    public function __construct(ContainerInterface $c)
    {
        $this->Utility = $c->get("UtilityProvider");
        $this->Context = $c->get("ContextProvider");
        $this->Settings = $c->get('settings');
        $this->Environment = $this->Settings['environment'];
    }

    use ViewModelPopulate, ErrorResultResponse;
}