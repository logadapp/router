<?php
declare(strict_types = 1);

namespace Logadapp\Router;

/**
 * Class Router
 * @author Michael Arawole <michael@logad.net>
 * @package logadapp
 */
final class Router
{
    private string $basePath = '';

    private array $handlers;

    private array $groupStack = [];

    private $notFoundHandler;
    
    private $notAllowedHandler;

    private $routeFoundHandler;

    private const METHOD_GET = 'GET';

    private const METHOD_POST = 'POST';

    private const METHOD_PATCH = 'PATCH';

    private const METHOD_DELETE = 'DELETE';

    private const METHOD_PUT = 'PUT';

    private const METHOD_OPTIONS = 'OPTIONS';

    public function setBasePath(string $basePath):void
    {
        $this->basePath = $basePath;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

