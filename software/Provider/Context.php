<?php


namespace Software\Provider;


use Exception as NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionObject;
use ReflectionProperty;
use Software\Entity\User\Context as User;

class Context
{
    /**
     * @var User
     */
    public $User;

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
            $context = $var->name."Context";
            if(!$c->has($context)) throw new NotFoundException("Missing context::{$context}");
            $this->{$var->name} = $c->get($context);
        }
    }
}