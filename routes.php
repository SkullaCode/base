<?php /** @noinspection ALL */

$app
    ->add(\App\MiddleWare\Transformation\ResponseHeader::class)
    ->add(\App\MiddleWare\Transformation\SessionTracker::class)
    ->add(\App\MiddleWare\Transformation\ModelMapping::class)
    ->add(\App\MiddleWare\Transformation\PopulateViewModel::class);

// Routes

$app->group('/list',function(){
    $this->get('/gender',\App\Controller\ListController::class.':Gender');
    $this->get('/country',\App\Controller\ListController::class.':Country');
    $this->get('/employer',\App\Controller\ListController::class.':Employer');
    $this->get('/states/{id}',\App\Controller\ListController::class.':States');
    $this->get('/contact-number',\App\Controller\ListController::class.':ContactNumber');
    $this->get('/employment-status',\App\Controller\ListController::class.':EmploymentStatus');
    $this->get('/marital-status',\App\Controller\ListController::class.':Marital');
    $this->get('/notification',\App\Controller\ListController::class.':Notification');
    $this->get('/proof-type',\App\Controller\ListController::class.':ProofType');
    $this->get('/occupation',\App\Controller\ListController::class.':Occupation');
    $this->get('/title',\App\Controller\ListController::class.':Title');
});

$container = $app->getContainer();
$environment = $container->get('settings')['config'];
$root_dir = $environment['root_directory'].DIRECTORY_SEPARATOR.'software';
$location = scandir($root_dir);
if(is_array($location) && count($location) > 2)
{
    foreach($location as $dir)
    {
        if($dir === '.' || $dir === '..') continue;
        $file = $root_dir.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.'routes.php';
        if(!file_exists($file)) continue;
        $route = require_once($file);
        if(is_array($route))
        {
            $app->group('/'.trim($route['route'],'/'),function() use($route){
                foreach($route['routes'] as $item)
                {
                    $r = $this->{$item['method']}($item['url'],$item['class'].':'.$item['action']);
                    if(is_array($item['middleware']) && count($item['middleware']) > 0)
                        foreach($item['middleware'] as $middleware)
                            $r->add($middleware);
                }
            });
        }
        $file = $root_dir.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.'load.php';
        if(!file_exists($file)) continue;
        require_once($file);
    }
}


$app->group('/cron',function(){

})
    ->add(\App\MiddleWare\Validation\IsCron::class);

$app->get('/file/{id}',\App\Controller\FileController::class);
$app->get('/',\App\Controller\AppController::class.':Load');
$app->get('/bootstrap-info',\App\Controller\AppController::class.':BootstrapInfo');
$app->get('/log-out',\App\Controller\AppController::class.':Kill');