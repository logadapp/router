<?php

use LogadApp\Router\Router;

require 'vendor/autoload.php';

$router = new Router();

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($scriptDir == "/") {
    $scriptDir = "";
}
$router->setBasePath($scriptDir);

$router->addMiddleware('auth', function () {
    echo 'Auth middleware called', PHP_EOL;
});

$router->get('/', function ($route) {
    echo 'Nice!', ' You got here.', ' Off to a good start..', PHP_EOL;
    echo 'Method: ', $route->getMethod(), PHP_EOL;
    echo 'Path: ', $route->getPath(), PHP_EOL;
    echo 'Arguments: ', json_encode($route->getArgs()), PHP_EOL;
})->middleware('auth');

$router->setRouteFoundHandler(function ($routeInfo) {
    $callback = $routeInfo['callback'];
    $route = $routeInfo['route'];

    call_user_func(
        $callback,
        route: $route
    );
});

$router->run();
