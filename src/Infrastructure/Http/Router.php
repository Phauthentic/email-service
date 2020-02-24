<?php
declare(strict_types=1);
/**
 * Copyright (c) Florian Krämer (https://florian-kraemer.net)
 * Licensed under The GPL3 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Florian Krämer (https://florian-kraemer.net)
 * @author        Florian Krämer
 * @link          https://github.com/Phauthentic
 * @license       https://opensource.org/licenses/GPL-3.0 GPL3 License
 */
namespace App\Infrastructure\Http;

use Psr\Http\Message\ServerRequestInterface;

/**
 * A primitive router, we don't need a full fledged router for this project
 *
 * All it does it matches a requests path via regex and the regex is mapped to
 * a handler class that is a string
 */
class Router
{
    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @param array $routes Map
     * @return $this
     */
    public static function fromArray(array $routes)
    {
        $router = new self();
        $router->routes = $routes;

        return $router;
    }

    /**
     * Add a regex
     *
     * @param string $method HTTP Method
     * @param string $pattern Regex Pattern
     * @param string $handlerClass Handler Class
     * @return $this
     */
    public function add(string $method, string $pattern, string $handlerClass): self
    {
        $this->routes[$method][$pattern] = $handlerClass;

        return $this;
    }

    /**
     * @param string $method Method
     * @return array
     */
    public function getMapForMethod(string $method): array
    {
        $method = strtolower($method);
        if (!isset($this->routes[$method])) {
            return [];
        }

        return $this->routes[$method];
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @return mixed
     */
    public function routeRequest(ServerRequestInterface $request)
    {
        $map = $this->getMapForMethod($request->getMethod());

        foreach ($map as $pattern => $handler) {
            $path = $request->getUri()->getPath();
            $method = $request->getMethod();

            if (preg_match($pattern, $path)) {
                return $handler;
            }
        }

        return null;
    }
}
