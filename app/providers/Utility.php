<?php


namespace App\Provider;


use App\Interfaces\IArrayFunction;
use App\Interfaces\ICodeGenerator;
use App\Interfaces\IConfiguration;
use App\Interfaces\IDateTime;
use App\Interfaces\IEmail;
use App\Interfaces\IFileSystem;
use App\Interfaces\IPassword;
use App\Interfaces\IRequest;
use App\Interfaces\ISession;
use App\Interfaces\ISetting;
use App\Interfaces\IStringFunction;
use App\Interfaces\IView;
use Exception as NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionObject;
use ReflectionProperty;

class Utility
{
    /**
     * @var ICodeGenerator
     */
    public $CodeGenerator;

    /**
     * @var IConfiguration
     */
    public $Configuration;

    /**
     * @var IDateTime
     */
    public $DateTime;

    /**
     * @var IRequest
     */
    public $Request;

    /**
     * @var IPassword
     */
    public $Password;

    /**
     * @var IEmail
     */
    public $Email;

    /**
     * @var IFileSystem
     */
    public $FileSystem;

    /**
     * @var ISession
     */
    public $Session;

    /**
     * @var IArrayFunction
     */
    public $ArrayFunction;

    /**
     * @var ISetting
     */
    public $Setting;

    /**
     * @var IStringFunction
     */
    public $StringFunction;

    /**
     * @var IView
     */
    public $View;

    /**
     * Context constructor.
     * @param ContainerInterface $c
     * @throws NotFoundException
     */
    public function __construct(ContainerInterface $c)
    {
        $vars = (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach($vars as $var)
        {
            $util = $var->name."Utility";
            if(!$c->has($util)) throw new NotFoundException("Missing utility::{$util}");
            $this->{$var->name} = $c->get($util);
        }
    }
}