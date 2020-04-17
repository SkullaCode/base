<?php


use Psr\Container\ContainerInterface;

/**
 * @var ContainerInterface $container
 */

$exclude = [
];

$environment = $container->get("settings")['config'];
$path = $environment['root_directory'].DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'utilities';

$files = scandir($path);
foreach($files as $file)
{
    $i = pathinfo($file);
    if (isset($i['extension']) && $i['extension'] == 'php')
    {
        if(in_array($i['filename'],$exclude))
            continue;
        $class = $i['filename'];
        $container->set($class.'Utility',function(ContainerInterface $c) use ($class){
            global $METHOD_CONTAINER;
            $namespace = "\\App\\Utility\\{$class}";
            if(!isset($METHOD_CONTAINER[$class.'Utility']))
                $METHOD_CONTAINER[$class.'Utility'] = new $namespace($c);
            return $METHOD_CONTAINER[$class.'Utility'];
        });
    }
}