<?php /** @noinspection ALL */

$app
    ->add(\App\MiddleWare\Transformation\ResponseHeader::class)
    ->add(\App\MiddleWare\Transformation\SessionTracker::class)
    ->add(\App\MiddleWare\Transformation\ModelMapping::class)
    ->add(\App\MiddleWare\Transformation\PopulateViewModel::class);

$container = $app->getContainer();
$environment = $container->get('settings')['config'];
$root_dir = $environment['root_directory'].DIRECTORY_SEPARATOR.'software';
$entity_dir = $root_dir.DIRECTORY_SEPARATOR.'Entity';
$location = scandir($entity_dir);
$routes = [];
if(is_array($location))
    foreach($location as $item)
        if(!in_array($item,['.','..']))
            $routes[] = $entity_dir.DIRECTORY_SEPARATOR.$item;
$routes[] = $root_dir.DIRECTORY_SEPARATOR.'Query';
$routes[] = $root_dir.DIRECTORY_SEPARATOR.'List';

foreach($routes as $dir)
{
    $file = $dir.DIRECTORY_SEPARATOR.'routes.php';
    if(!file_exists($file)) continue;
    $route = require_once($file);
    if(is_array($route))
    {
        $g = $app->group('/'.trim($route['route'],'/'),function() use($route){
            foreach($route['routes'] as $item)
            {
                $r = $this->{$item['method']}($item['url'],$item['class'].':'.$item['action']);
                if(is_array($item['middleware']) && !empty($item['middleware']))
                    foreach($item['middleware'] as $middleware)
                        $r->add($middleware);
            }
        });
        if(is_array($route['middleware']) && !empty($route['middleware']))
            foreach($route['middleware'] as $middleware)
                $g->add($middleware);
    }
    //This is not a part of the route but it makes sense to put it here
    //so that the directory is not looped more than once
    $file = $dir.DIRECTORY_SEPARATOR.'load.php';
    if(!file_exists($file)) continue;
    require_once($file);
}

$app->get('/',\App\Controller\AppController::class.':Load');
$app->get('/bootstrap-info',\App\Controller\AppController::class.':BootstrapInfo');
$app->get('/log-out',\App\Controller\AppController::class.':Kill');
$app->get('/test',\Software\Test\Controller::class);
