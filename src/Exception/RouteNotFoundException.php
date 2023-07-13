<?php

namespace LogadApp\Router\Exception;

use Exception;

class RouteNotFoundException extends Exception
{
    public function __construct(string $route = "", int $code = 0, Exception $previous = null)
    {
        $message = "Route `$route` not found";
        parent::__construct($message, $code, $previous);
    }
}
