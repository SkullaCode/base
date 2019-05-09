<?php

use Slim\App;
use Slim\Middleware\Session;

require __DIR__ . './vendor/autoload.php';

// Instantiate the app to run
if(!file_exists(__DIR__ . './settings.php'))
{
    header("HTTP/1.1 500 Settings Not Found");
    exit();
}
$settings = require __DIR__ . './settings.php';

try
{
    $app = new App($settings);
}
catch(Exception $e)
{
    header("HTTP/1.1 500 Internal Server Error");
    exit();
}

// session_start() MUST be called after class instantiation
// else it will throw a warning on newer versions of PHP
$app->add(new Session([
    'name' => md5('app'),
    'autorefresh' => true,
    'lifetime' => '1 hour'
]));

$container = $app->getContainer();
global $METHOD_CONTAINER;
$METHOD_CONTAINER = [];

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