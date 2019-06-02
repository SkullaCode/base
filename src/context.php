<?php

use Psr\Container\ContainerInterface;

$environment = $container->get("settings")['config'];
$root_dir = $environment['root_directory'].DIRECTORY_SEPARATOR.'software'.DIRECTORY_SEPARATOR.'Entity';
$location = scandir($root_dir);
if(is_array($location) && count($location) > 2)
{
    foreach($location as $dir)
    {
        if($dir === '.' || $dir === '..') continue;
        $file = $root_dir.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.'Context.php';
        if(!file_exists($file)) continue;
        $namespaceString = "\\Software\\Entity\\{$dir}\\";
        $container[$dir.'Context'] = function(ContainerInterface $c) use ($dir,$namespaceString){
            global $METHOD_CONTAINER;
            $namespace = "{$namespaceString}Context";
            if(!isset($METHOD_CONTAINER[$dir.'Context']))
                $METHOD_CONTAINER[$dir.'Context'] = new $namespace($c);
            return $METHOD_CONTAINER[$dir.'Context'];
        };
    }
}
$dir = $environment['root_directory'].DIRECTORY_SEPARATOR.'software'.DIRECTORY_SEPARATOR.'File';
$file = $dir.DIRECTORY_SEPARATOR.'Context.php';
if(file_exists($file))
{
    $namespaceString = "\\Software\\File\\";
    $container['FileContext'] = function(ContainerInterface $c) use ($namespaceString){
        global $METHOD_CONTAINER;
        $namespace = "{$namespaceString}Context";
        if(!isset($METHOD_CONTAINER['FileContext']))
            $METHOD_CONTAINER['FileContext'] = new $namespace($c);
        return $METHOD_CONTAINER['FileContext'];
    };
}