<?php

use LogadApp\Router\Router;

require 'vendor/autoload.php';

$router = new Router();

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($scriptDir == "/") {
    $scriptDir = "";
}
$router->setBasePath($scriptDir);

$router->get('/', function ($route) {
    echo 'Nice!', ' You got here.', ' Off to a good start..', PHP_EOL;
    echo 'Method: ', $route->getMethod(), PHP_EOL;
    echo 'Path: ', $route->getPath(), PHP_EOL;
    echo 'Arguments: ', json_encode($route->getArguments()), PHP_EOL;
});

$router->setRouteFoundHandler(function ($routeInfo) {
    $callback = $routeInfo['callback'];
    $route = $routeInfo['route'];

    call_user_func(
        $callback,
        route: $route
    );
});

$router->run();
