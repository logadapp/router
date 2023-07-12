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
