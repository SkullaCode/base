<?php


use App\Provider\Utility;
use Psr\Container\ContainerInterface;
use Software\Provider\Context;

/**
 * @var ContainerInterface $container
 */

$exclude = [

];

$environment = $container->get("settings")['config'];
$context_path = $environment['root_directory'].
    DIRECTORY_SEPARATOR.
    'software'.
    DIRECTORY_SEPARATOR.
    'Provider'.
    DIRECTORY_SEPARATOR.
    'Context.php';
$utility_path = $environment['root_directory'].
    DIRECTORY_SEPARATOR.
    'app'.
    DIRECTORY_SEPARATOR.
    'providers'.
    DIRECTORY_SEPARATOR.
    'Utility.php';

if(!file_exists($context_path)) throw new Exception("Context provider missing");
$container->set('ContextProvider',function(ContainerInterface $c){
    global $METHOD_CONTAINER;
    if(!isset($METHOD_CONTAINER['ContextProvider']))
        $METHOD_CONTAINER['ContextProvider'] = new Context($c);
    return $METHOD_CONTAINER['ContextProvider'];
});

if(!file_exists($utility_path)) throw new Exception("Utility provider missing");
$container->set('UtilityProvider',function(ContainerInterface $c){
    global $METHOD_CONTAINER;
    if(!isset($METHOD_CONTAINER['UtilityProvider']))
        $METHOD_CONTAINER['UtilityProvider'] = new Utility($c);
    return $METHOD_CONTAINER['UtilityProvider'];
});