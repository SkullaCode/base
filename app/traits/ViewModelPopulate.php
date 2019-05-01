<?php


namespace App\Traits;


use ReflectionObject;
use ReflectionProperty;
use Psr\Http\Message\ServerRequestInterface as Request;
use stdClass;

trait ViewModelPopulate
{
    protected function Populate($model,Request $rq)
    {
        $vars = (new ReflectionObject($model))->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach($vars as $var)
        {
            if(!is_null($rq->getAttribute($var->name,null)))
            {
                $model->{$var->name} = $rq->getAttribute($var->name);
                continue;
            }
            $params = $rq->getParsedBody();
            if(in_array($var->name,array_keys($params)))
            {
                $model->{$var->name} = $params[$var->name];
                continue;
            }
            $params = $rq->getQueryParams();
            if(in_array($var->name,array_keys($params)))
            {
                $model->{$var->name} = $params[$var->name];
                continue;
            }
            $model->{$var->name} = null;
        }
        return $model;
    }

    protected function Scrape(Request $rq)
    {
        $elements = new StdClass();
        //$a = (is_array($rq->getAttributes())) ? $rq->getAttributes() : [];
        $p = (is_array($rq->getParsedBody())) ? $rq->getParsedBody() : [];
        $q = (is_array($rq->getQueryParams())) ? $rq->getQueryParams() : [];
        $attributes = array_merge($p,$q);
        foreach($attributes as $key => $val) { $elements->{$key} = $val; }
        return $elements;
    }
}