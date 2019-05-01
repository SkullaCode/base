<?php


$exclude = [
];

$environment = $container->get("settings")['config'];
$path = $environment['utility_directory'];

$files = scandir($path);
foreach($files as $file)
{
    $i = pathinfo($file);
    if (isset($i['extension']) && $i['extension'] == 'php')
    {
        if(in_array($i['filename'],$exclude))
            continue;
        $class = $i['filename'];
        $container[$class.'Utility'] = function(\Psr\Container\ContainerInterface $c) use ($class){
            global $METHOD_CONTAINER;
            $namespace = "\\App\\Utility\\{$class}";
            if(!isset($METHOD_CONTAINER[$class.'Utility']))
                $METHOD_CONTAINER[$class.'Utility'] = new $namespace($c);

            return $METHOD_CONTAINER[$class.'Utility'];
        };
    }
}