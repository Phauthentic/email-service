<?php

declare(strict_types=1);

/**
 * Copyright (c) Florian Krämer (https://florian-kraemer.net)
 *
 * Licensed under The GPL3 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Florian Krämer (https://florian-kraemer.net)
 * @author        Florian Krämer
 * @link          https://github.com/Phauthentic
 * @license       https://opensource.org/licenses/GPL-3.0 GPL3 License
 */

namespace App\Test\TestCase\Infrastructure\Http;

use App\Infrastructure\Http\Router;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Closure;

/**
 * Router Test
 */
class RouterTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreating(): void
    {
        $router = Router::fromArray([
            'get' => [
                '/users/' => 'UsersHandlerClass'
            ]
        ]);

        $request = $this->buildMockRequest('/users', 'get');
        $result = $router->routeRequest($request);
        $this->assertEquals('UsersHandlerClass', $result);
    }

    /**
     * @return void
     */
    public function testRoutes(): void
    {
        $router = Router::fromArray([
            'get' => [
                '/callback(\/)?$/' => function() {}
            ]
        ]);

        $request = $this->buildMockRequest('/callback', 'get');
        $result = $router->routeRequest($request);
        $this->assertInstanceOf(Closure::class, $result);

        $request = $this->buildMockRequest('/callback', 'get');
        $result = $router->routeRequest($request);
        $this->assertInstanceOf(Closure::class, $result);

        $request = $this->buildMockRequest('/callback/', 'get');
        $result = $router->routeRequest($request);
        $this->assertInstanceOf(Closure::class, $result);

        $request = $this->buildMockRequest('/callback/more', 'get');
        $result = $router->routeRequest($request);
        $this->assertNull($result);
    }

    /**
     * @return MockObject
     */
    protected function buildMockRequest($path, $method): MockObject
    {
        $uri = $this->getMockBuilder(UriInterface::class)
            ->getMock();
        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();

        $request->expects($this->any())
            ->method('getMethod')
            ->willReturn($method);

        $request->expects($this->any())
            ->method('getUri')
            ->willReturn($uri);

        $uri->expects($this->any())
            ->method('getPath')
            ->willReturn($path);

        return $request;
    }
}
