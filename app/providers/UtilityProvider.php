<?php


namespace App\Provider;


use App\Utility\ArrayFunction;
use App\Utility\CodeGenerator;
use App\Utility\Configuration;
use App\Utility\DateTime;
use App\Utility\Email;
use App\Utility\FileSystem;
use App\Utility\Password;
use App\Utility\Request;
use App\Utility\Session;
use Psr\Container\ContainerInterface;
use ReflectionObject;
use ReflectionProperty;


class UtilityProvider
{

    /**
     * @var CodeGenerator
     */
    public $CodeGenerator;

    /**
     * @var Configuration
     */
    public $Configuration;

    /**
     * @var DateTime
     */
    public $DateTime;

    /**
     * @var Request
     */
    public $Request;

    /**
     * @var Password
     */
    public $Password;

    /**
     * @var Email
     */
    public $Email;

    /**
     * @var FileSystem
     */
    public $FileSystem;

    /**
     * @var Session
     */
    public $Session;

    /**
     * @var ArrayFunction
     */
    public $ArrayFunction;

    public function __construct(ContainerInterface $c)
    {
        $vars = (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach($vars as $var)
        {
            $util = $var->name."Utility";
            $this->{$var->name} = $c->get($util);
        }
    }
}