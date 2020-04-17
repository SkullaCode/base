<?php


namespace App\Traits;


use App\Constant\ModelMapping;
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
        if(strtolower($rq->getMethod()) !== ModelMapping::UPDATING && strtolower($rq->getMethod()) !== ModelMapping::READING)
        {
            $s = [];
            $stream = $rq->getBody();
            if($stream->isReadable())
            {
                $stream = rawurldecode($stream->getContents());
                if(!empty($stream))
                {
                    if($this->isFormDataRequest())
                    {
                        $s = $this->parseFormData($stream);
                    }
                    else if($this->isUrlEncodedRequest())
                    {
                        $params = explode('&',$stream);
                        if(is_array($params) && !empty($params))
                        {
                            foreach($params as $param)
                            {
                                $p = explode('=',$param);
                                $s[$p[0]] = $p[1];
                            }
                        }
                    }
                }
            }
            $body = $s;
        }
        else
        {
            $body = $rq->getParsedBody();
        }
        $query = $rq->getQueryParams();
        //$a = (is_array($rq->getAttributes())) ? $rq->getAttributes() : [];
        $p = (is_array($body) && !empty($body)) ? $body : [];
        $q = (is_array($query) && !empty($query)) ? $query : [];
        $attributes = array_merge($p,$q);
        foreach($attributes as $key => $val) { $elements->{$key} = $val; }
        return $elements;
    }

    private function isFormDataRequest()
    {
        $sample = substr($_SERVER['HTTP_CONTENT_TYPE'],0,9);
        return ($sample === 'multipart');
    }

    private function isUrlEncodedRequest()
    {
        return ($_SERVER['HTTP_CONTENT_TYPE'] === 'application/x-www-form-urlencoded');
    }

    function parseFormData($stream)
    {
        $a_data = [];
        // grab multipart boundary from content type header
        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);

        $boundary = $matches[1];

        // split content by boundary and get rid of last -- element
        $a_blocks = preg_split("/-+$boundary/", $stream);
        array_pop($a_blocks);

        $keyValueStr = '';
        // loop data blocks
        foreach ($a_blocks as $id => $block)
        {
            if (empty($block))
                continue;

            // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char

            // parse uploaded files
            if (strpos($block, 'application/octet-stream') !== FALSE)
            {
                // match "name", then everything after "stream" (optional) except for prepending newlines
                preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
                $a_data['files'][$matches[1]] = $matches[2];
            }
            // parse all other fields
            else
            {
                // match "name" and optional value in between newline sequences
                preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
                $keyValueStr .= $matches[1]."=".$matches[2]."&";
            }
        }
        $keyValueArr = [];
        parse_str($keyValueStr, $keyValueArr);
        return array_merge($a_data, $keyValueArr);
    }
}