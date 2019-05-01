<?php


namespace App\Utility;


class ArrayFunction
{
    public function ObjectToArray($obj)
    {
        if(is_object($obj))
        {
            $obj = (array)$obj;
        }
        if(is_array($obj))
        {
            $new = array();
            foreach($obj as $key => $val)
            {
                $new[$key] = $this->ObjectToArray($val);
            }
        }
        else
        {
            $new = $obj;
        }
        return $new;
    }
}