<?php


use Psr\Container\ContainerInterface;
use Software\Provider\Context;
use Software\Provider\Utility;

$exclude = [

];
$environment = $container->get("settings")['config'];
$path = $environment['root_directory'].
    DIRECTORY_SEPARATOR.
    'software'.
    DIRECTORY_SEPARATOR.
    'Provider'.
    DIRECTORY_SEPARATOR;
$context_path = $path.'Context.php';
$utility_path = $path.'Utility.php';

if(!file_exists($context_path)) throw new Exception("Context provider missing");
$container['ContextProvider'] = function(ContainerInterface $c){
    global $METHOD_CONTAINER;
    if(!isset($METHOD_CONTAINER['ContextProvider']))
        $METHOD_CONTAINER['ContextProvider'] = new Context($c);

    return $METHOD_CONTAINER['ContextProvider'];
};

if(!file_exists($utility_path)) throw new Exception("Utility provider missing");
$container['UtilityProvider'] = function(ContainerInterface $c){
    global $METHOD_CONTAINER;
    if(!isset($METHOD_CONTAINER['UtilityProvider']))
        $METHOD_CONTAINER['UtilityProvider'] = new Utility($c);

    return $METHOD_CONTAINER['UtilityProvider'];
};