<?php


namespace App\Constant;


use App\ViewModel\Def\SelectListModel;

class CodeBase
{
    /**
     * @var array d
     */
    protected static $Mapper = [];

    public static function init($m,$class)
    {
        self::$Mapper[$class] = $m;
    }

    /**
     * @param int $c
     * @return string
     */
    public static function ToString($c)
    {
        try {
            $class = new \ReflectionClass(static::class);
        } catch (\ReflectionException $e) {
            return "Undefined";
        }
        return (array_key_exists($c,self::$Mapper[$class->name]))
            ? self::$Mapper[$class->name][$c]
            : "Undefined"
        ;
    }

    /**
     * @return array
     */
    public static function ToList()
    {
        $result = [];
        try {
            $class = new \ReflectionClass(static::class);
        } catch (\ReflectionException $e) {
            return $result;
        }
        if(isset(self::$Mapper[$class->name]) && count(self::$Mapper[$class->name]) > 0)
        {
            foreach(self::$Mapper[$class->name] as $key => $val)
            {
                /*$parts = explode('_',$key);
                $parts = array_map(function($e){ return ucfirst(strtolower($e));},$parts);
                $parts = implode(' ',$parts);*/
                $model = new SelectListModel();
                $model->Text = $val;
                $model->Value = $key;
                $result[] = $model;
            }
        }
        return $result;
    }

    /**
     * @param int $c
     * @return boolean
     */
    public static function Exists($c)
    {
        try {
            $class = new \ReflectionClass(static::class);
        } catch (\ReflectionException $e) {
            return false;
        }
        return (array_key_exists($c,self::$Mapper[$class->name]));
    }
}