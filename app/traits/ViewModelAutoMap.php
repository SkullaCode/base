<?php


namespace App\Traits;


use ReflectionObject;
use ReflectionProperty;

trait ViewModelAutoMap
{
    protected function AutoMap($viewModel)
    {
        if(is_object($this) && is_object($viewModel))
        {
            $vars = (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC);
            foreach($vars as $var)
                if(property_exists($viewModel,$var->name))
                    $this->{$var->name} = $viewModel->{$var->name};
        }
    }

    protected function Map($model, $viewModel)
    {
        if(is_object($model) && is_object($viewModel))
        {
            $vars = (new ReflectionObject($model))->getProperties(ReflectionProperty::IS_PUBLIC);
            foreach($vars as $var)
                if(property_exists($viewModel,$var->name))
                    $model->{$var->name} = $viewModel->{$var->name};
        }
    }
}