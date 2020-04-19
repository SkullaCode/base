<?php

use App\Provider\AjaxErrorRenderer;
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;

require __DIR__ . './vendor/autoload.php';

//session_start();

// Instantiate the app to run
if(!file_exists(__DIR__ . './settings.php'))
{
    header("HTTP/1.0 500 Settings Not Found");
    exit();
}
$settings = require __DIR__ . './settings.php';

try
{
    $container = new Container();
    AppFactory::setContainer($container);
    $app = AppFactory::create();
}
catch(InvalidArgumentException $e)
{
    header("HTTP/1.0 500 Internal Server Error");
    exit();
}

// session_start() MUST be called after class instantiation
// else it will throw a warning on newer versions of PHP
$app->add(new \Slim\Middleware\Session([
    'name' => md5('app'),
    'autorefresh' => true,
    'lifetime' => '1 hour'
]));

$app->addRoutingMiddleware();

$methodOverrideMiddleware = new MethodOverrideMiddleware();
$app->add($methodOverrideMiddleware);


$inactive = session_status() === PHP_SESSION_NONE;
if ($inactive) session_start();

$container = $app->getContainer();
$container->set("settings",$settings['settings']);
global $METHOD_CONTAINER;
$METHOD_CONTAINER = [];

define("ROOT_FOLDER",rtrim(__DIR__,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR);

// Set up dependencies
require __DIR__ . './src/drivers.php';

require __DIR__ . './src/utilities.php';

require __DIR__ . './src/context.php';

require __DIR__ . './src/providers.php';

require __DIR__ . './src/initialization.php';

// Register middleware
//require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . './routes.php';

//$app->addRoutingMiddleware();

$e = $app->addErrorMiddleware(true,false,false);
$handler = $e->getDefaultErrorHandler();
$handler->registerErrorRenderer('application/json',AjaxErrorRenderer::class);
//force JSON response for AJAX requests
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
{
    $handler->forceContentType('application/json');
}