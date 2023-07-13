<?php
declare(strict_types = 1);

namespace LogadApp\Router;

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

    /**
     * 404 handler
     * @param callable $handler
     * @return self
     */
    public function setNotFoundHandler(callable $handler):self
    {
        $this->notFoundHandler = $handler;
        return $this;
    }

    public function getNotFoundHandler(): callable
    {
        return $this->notFoundHandler;
    }

    /**
     * 405 handler
     * @param callable $handler
     * @return self
     */
    public function setNotAllowedHandler(callable $handler):self
    {
        $this->notAllowedHandler = $handler;
        return $this;
    }

    public function getNotAllowedHandler(): callable
    {
        return $this->notAllowedHandler;
    }

    /**
     * When a route is found
     * @param callable $handler
     * @return self
     */
    public function setRouteFoundHandler(callable $handler):self
    {
        $this->routeFoundHandler = $handler;
        return $this;
    }

    ## GET HTTP method ##
    public function get(string $path, callable $handler):void
    {
        $this->addHandler(self::METHOD_GET, $path, $handler);
    }

    ## POST HTTP method ##
    public function post(string $path, callable $handler):void
    {
        $this->addHandler(self::METHOD_POST, $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     * @return void
     */
    public function patch(string $path, callable $handler):void
    {
        $this->addHandler(self::METHOD_PATCH, $path, $handler);
    }

    /**
     * @param string $path
     * @param callable $handler
     * @return void
     */
    public function delete(string $path, callable $handler):void
    {
        $this->addHandler(self::METHOD_DELETE, $path, $handler);
    }

    /**
     * Group common routes
     * @param string $prefix
     * @param callable $callback
     * @return void
     */
    public function group(string $prefix, callable $callback): void
    {
        // Push the current group prefix to the stack
        $this->groupStack[] = $prefix;

        // Execute the callback to define endpoints within the group
        $callback();

        // Pop the last group prefix from the stack
        array_pop($this->groupStack);
    }

    ## Add Handler ##
    private function addHandler(string $method, string $path, callable $handler):void
    {
        $prefix = implode('', $this->groupStack);
        $path = $prefix . $path;
        $regex = null;
        // named variables
        if (strpos($path, '{')) {
            $regex = preg_replace('/{([a-zA-Z_]+)}/', "(?P<$1>[a-zA-Z0-9_-]+)", $path);
            $regex = str_replace('/(', '\/(', $regex);
        }

        $this->handlers[$method . $path] = [
            'path' => $this->basePath.$path,
            'method' => $method,
            'handler' => $handler,
            'regex' => $regex
        ];
    }

    ## Deploy ##
    public function run(): void
    {
        $requestUrl = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUrl['path'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $callback = null;
        $args = [];
        $methodNotAllowed = false;

        foreach ($this->handlers as $handler) {
            if (!empty($handler['regex']) && $handler['method'] === $requestMethod) {
                $regex = trim($handler['regex'], '/');
                if (preg_match("%$regex%", $requestPath, $matches)) {
                    foreach ($matches as $mKey => $value) {
                        if (is_numeric($mKey)) {
                            continue;
                        }
                        $args[$mKey] = $value;
                        $callback = $handler['handler'];
                    }
                }
            } else {
                if ($handler['path'] === $requestPath && $handler['method'] === $requestMethod) {
                    $callback = $handler['handler'];
                } elseif ($handler['path'] === $requestPath) {
                    $methodNotAllowed = true;
                }
            }
        }

        if ($methodNotAllowed) {
            if (!empty($this->notAllowedHandler)) {
                call_user_func($this->notAllowedHandler, $requestPath);
            } else {
                http_response_code(405);
                throw new \Exception("Method not allowed for $requestPath");
            }
            return;
        }

        // Classes as callback
        if (is_array($callback)) {
            $className = array_shift($callback);
            $handler = new $className;
            $methodFunction = array_shift($callback);
            $callback = [$handler, $methodFunction];
        }

        if (!$callback) {
            if (!empty($this->notFoundHandler)) {
                call_user_func($this->notFoundHandler, $requestPath);
                return;
            } else {
                http_response_code(404);
            }
        }

        if (!empty($this->routeFoundHandler)) {
            call_user_func_array($this->routeFoundHandler, [
                'method' => $requestMethod,
                'path' => $requestPath,
                'url' => $requestUrl,
                'args' => $args,
                'callback' => $callback
            ]);
        } else {
            call_user_func_array($callback, [
                $args
            ]);
        }
    }
}
