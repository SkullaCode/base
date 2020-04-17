<?php


namespace App\ViewModel;


class ViewModel
{
    private $container;

    public function __construct($container = null)
    {
        if(!is_null($container))
            $this->container = (array)$container;
    }

    public function __get($name)
    {
        return (array_key_exists($name,$this->container)) ? $this->container[$name] : null;
    }

    public function __set($name, $value)
    {
        $this->container[$name] = $value;
    }

    public function AddContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function ToArray()
    {
        $holder = [];
        if(!empty($this->container))
            foreach($this->container as $key => $val)
                $holder[$key] = $val;
        return $holder;
    }

    /**
     * @return object
     */
    public function ToObject()
    {
        return (object)$this->ToArray();
    }
}