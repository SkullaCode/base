<?php


namespace App\Provider;

use App\Interfaces\Context\IFile;
use App\Interfaces\Context\IState;
use App\Interfaces\Context\IDbContext;
use Psr\Container\ContainerInterface;
use ReflectionObject;
use ReflectionProperty;
use Software\Brand\IContext as IBrand;
use Software\Decoration\IContext as IDecoration;
use Software\GiftContainer\IContext as IGiftContainer;
use Software\GiftItem\IContext as IGiftItem;
use Software\Location\IContext as ILocation;
use Software\Package\IContext as IPackage;
use Software\Purchase\IContext as IPurchase;

class ContextProvider
{
    /**
     * @var IFile|null
     */
    public $File;

    /**
     * @var IDbContext|null
     */
    public $Country;

    /**
     * @var IState|null
     */
    public $State;

    /**
     * @var IBrand|null
     */
    public $Brand;

    /**
     * @var IDecoration|null
     */
    public $Decoration;

    /**
     * @var IGiftContainer|null
     */
    public $GiftContainer;

    /**
     * @var IGiftItem|null
     */
    public $GiftItem;

    /**
     * @var ILocation|null
     */
    public $Location;

    /**
     * @var IPackage|null
     */
    public $Package;

    /**
     * @var IPurchase|null
     */
    public $Purchase;

    public function __construct(ContainerInterface $c)
    {
        $vars = (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach($vars as $var)
        {
            $context = $var->name."Context";
            $this->{$var->name} = ($c->has($context))
                ? $c->get($context)
                : null;
        }
    }
}