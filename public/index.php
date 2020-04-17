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

require_once pathinfo($_SERVER['DOCUMENT_ROOT'],PATHINFO_DIRNAME).DIRECTORY_SEPARATOR.'loader.php';

// Run app
//todo write errors to log file
try {
    $app->run();
}
catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    exit();
}