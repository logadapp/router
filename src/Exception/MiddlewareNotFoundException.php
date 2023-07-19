<?php

namespace LogadApp\Router\Exception;

use Exception;

class MiddlewareNotFoundException extends Exception
{
    public function __construct(string $name = "", int $code = 0, Exception $previous = null)
    {
        $message = "Middleware `$name` not found";
        parent::__construct($message, $code, $previous);
    }
}
