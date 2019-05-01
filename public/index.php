<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

//session_start();

// Instantiate the app to run
if(!file_exists(__DIR__ . '/../settings.php'))
{
    header("HTTP/1.0 500 Settings Not Found");
    exit();
}
$settings = require __DIR__ . '/../settings.php';

try
{
    $app = new \Slim\App($settings);
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

$container = $app->getContainer();
global $METHOD_CONTAINER;
$METHOD_CONTAINER = [];

// Set up dependencies
require __DIR__ . '/../src/drivers.php';

require __DIR__ . '/../src/utilities.php';

require __DIR__ . '/../src/context.php';

require __DIR__ . '/../src/providers.php';

require __DIR__ . '/../src/initialization.php';

// Register middleware
//require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../routes.php';

// Run app
//todo write errors to log file
try {
    session_start();
    $app->run();
} catch (\Slim\Exception\MethodNotAllowedException $e) {
    header("HTTP/1.1 500 Method Not Allowed");
    exit();
} catch (\Slim\Exception\NotFoundException $e) {
    header("HTTP/1.1 404 Not Found");
    exit();
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    exit();
}