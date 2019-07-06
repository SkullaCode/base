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

require_once './loader.php';

// Run app
//todo write errors to log file
try {
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