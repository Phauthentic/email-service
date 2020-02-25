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

use InvalidArgumentException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;

/**
 * A primitive router, fully regex based, no hand-holding :)
 *
 * All it does it matches a requests path via regex and the regex is mapped to
 * a handler
 */
class Router
{
    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @var array
     */
    protected $httpVerbs = ['post', 'put', 'get', 'delete', 'patch'];

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
     * @param mixed $handler Handler
     * @return $this
     */
    public function addRoute(string $method, string $pattern, $handler): self
    {
        $method = strtolower($method);
        $this->checkHttpVerb($method);

        if (isset($this->routes[$method][$pattern])) {
            throw new RuntimeException(stprintf(
                'Route %s for %s has already been defined',
                $pattern,
                $method
            ));
        }

        $this->routes[$method][$pattern] = $handler;

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

            if (preg_match($pattern, $path)) {
                return $handler;
            }
        }

        return null;
    }

    /**
     * @param string $verb Verb
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function checkHttpVerb($verb): void
    {
        if (!in_array($verb, $this->httpVerbs)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid HTTP verb %s provided', $verb
            ));
        }
    }
}
