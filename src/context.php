<?php

use Psr\Container\ContainerInterface;

$environment = $container->get("settings")['config'];

if($environment['mode'] === 'production')
{
    $root_dir = $environment['root_directory'].DIRECTORY_SEPARATOR.'software';
    $location = scandir($root_dir);
    if(is_array($location) && count($location) > 2)
    {
        foreach($location as $dir)
        {
            if($dir === '.' || $dir === '..') continue;
            $file = $root_dir.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.'Context.php';
            if(!file_exists($file)) continue;
            $namespaceString = "\\Software\\{$dir}\\";
            $container[$dir.'Context'] = function(ContainerInterface $c) use ($dir,$namespaceString){
                global $METHOD_CONTAINER;
                $namespace = "{$namespaceString}Context";
                if(!isset($METHOD_CONTAINER[$dir.'Context']))
                    $METHOD_CONTAINER[$dir.'Context'] = new $namespace($c);
                return $METHOD_CONTAINER[$dir.'Context'];
            };
        }
    }
}
else
{
    $exclude = [
        'BaseDbContext'
    ];

    $path = $environment['root_directory'].DIRECTORY_SEPARATOR.'dev'.DIRECTORY_SEPARATOR.'contexts';
    $namespaceString = "\\Development\\DBContext\\";
    $files = scandir($path);
    foreach($files as $file)
    {
        $i = pathinfo($file);
        if (isset($i['extension']) && $i['extension'] == 'php')
        {
            if(in_array($i['filename'],$exclude))
                continue;
            $class = $i['filename'];
            $container[$class.'Context'] = function(ContainerInterface $c) use ($class,$namespaceString){
                global $METHOD_CONTAINER;
                $namespace = "{$namespaceString}{$class}";
                if(!isset($METHOD_CONTAINER[$class.'Context']))
                    $METHOD_CONTAINER[$class.'Context'] = new $namespace($c);
                return $METHOD_CONTAINER[$class.'Context'];
            };
        }
    }
}