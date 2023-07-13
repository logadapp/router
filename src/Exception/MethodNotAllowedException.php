<?php

declare(strict_types=1);

namespace LogadApp\Router\Exception;

use Exception;

class MethodNotAllowedException extends Exception
{
    public function __construct(string $route = "", int $code = 0, Exception $previous = null)
    {
        $message = "Method not allowed for `$route`";
        parent::__construct($message, $code, $previous);
    }
}
