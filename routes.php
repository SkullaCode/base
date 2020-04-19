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
$routes[] = $root_dir.DIRECTORY_SEPARATOR.'Lists';
$routes[] = $root_dir.DIRECTORY_SEPARATOR.'Query';
$routes[] = $root_dir.DIRECTORY_SEPARATOR.'UI';
$routes[] = $root_dir.DIRECTORY_SEPARATOR.'Settings';

foreach($routes as $dir)
{
    $file = $dir.DIRECTORY_SEPARATOR.'routes.php';
    if(!file_exists($file)) continue;
    $route = require_once($file);
    if(is_array($route))
    {
        $g = $app->group('/'.trim($route['route'],'/'),function(\Slim\Interfaces\RouteCollectorProxyInterface $group) use($route){
            foreach($route['routes'] as $item)
            {
                $r = $group->{$item['method']}($item['url'],$item['class'].':'.$item['action']);
                if(isset($item['access']) && is_array($item['access']) && !empty($item['access']))
                {
                    //add developer privilege to each route item
                    $item['access'][] = \Software\Entity\User\Constants\PrivilegeCode::DEVELOPER;
                    $hash = md5(trim($route['route'],'/').trim($item['url'],'/').$item['method']);
                    $r->add(\App\MiddleWare\Validation\AccessRights::class)
                        ->add(new \App\MiddleWare\Validation\AddPrivilegeToRoute(\App\Constant\RequestModel::ACCESS_RIGHTS_METHOD,$item['access']));

                }
                if(isset($item['middleware']) &&  is_array($item['middleware']) && !empty($item['middleware']))
                    foreach($item['middleware'] as $middleware)
                        $r->add($middleware);
            }
        });
        if(isset($route['access']) && is_array($route['access']) && !empty($route['access']))
        {
            //add developer privilege to each route
            $route['access'][] = \Software\Entity\User\Constants\PrivilegeCode::DEVELOPER;
            $hash = md5(trim($route['route'],'/'));
            $g->add(\App\MiddleWare\Validation\AccessRights::class)
                ->add(new \App\MiddleWare\Validation\AddPrivilegeToRoute(\App\Constant\RequestModel::ACCESS_RIGHTS_CONTROLLER,$route['access']));
        }
        if(isset($route['middleware']) && is_array($route['middleware']) && !empty($route['middleware']))
            foreach($route['middleware'] as $middleware)
                $g->add($middleware);
    }
    //This is not a part of the route but it makes sense to put it here
    //so that the directory is not looped more than once
    $file = $dir.DIRECTORY_SEPARATOR.'load.php';
    if(!file_exists($file)) continue;
    require_once($file);
}

$app->group('/update',function(\Slim\Interfaces\RouteCollectorProxyInterface $group){
    $group->post('',\App\Controller\AppController::class.':Update')
        ->add(\App\MiddleWare\Update\UploadUpdatePackage::class)
        ->add(\App\MiddleWare\Update\WorkAreaSetup::class)
        ->add(\App\MiddleWare\Validation\SessionExists::class);
    $group->post('/bootstrap-update',\App\Controller\AppController::class.':Bootstrap')
        ->add(\App\MiddleWare\Simulation\SlowNetworkConnection::class)
        ->add(\App\MiddleWare\Validation\SessionExists::class);
    $group->post('/extract-update',\App\Controller\AppController::class.':Extract')
        ->add(\App\MiddleWare\Update\ExtractUpdatePackage::class)
        ->add(\App\MiddleWare\Update\LoadFileSystemLocations::class)
        ->add(\App\MiddleWare\Validation\SessionExists::class);
    $group->post('/backup-update',\App\Controller\AppController::class.':Backup')
        ->add(\App\MiddleWare\Simulation\SlowNetworkConnection::class)
        ->add(\App\MiddleWare\Validation\SessionExists::class);
    $group->post('/execute-update',\App\Controller\AppController::class.':Execute')
        ->add(\App\MiddleWare\Update\GetExecutableFile::class)
        ->add(\App\MiddleWare\Update\LoadFileSystemLocations::class)
        ->add(\App\MiddleWare\Validation\SessionExists::class);
    $group->post('/cleanup-update',\App\Controller\AppController::class.':Cleanup')
        ->add(\App\MiddleWare\Update\GetExecutableFile::class)
        ->add(\App\MiddleWare\Update\LoadFileSystemLocations::class)
        ->add(\App\MiddleWare\Validation\SessionExists::class);
})
    ->add(\App\MiddleWare\Validation\AccessRights::class)
    ->add(new \App\MiddleWare\Validation\AddPrivilegeToRoute(\App\Constant\RequestModel::ACCESS_RIGHTS_CONTROLLER,[
        \Software\Entity\User\Constants\PrivilegeCode::DEVELOPER,
        \Software\Entity\User\Constants\PrivilegeCode::ADMIN
    ]));