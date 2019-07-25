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
namespace App\Infrastructure\Http\Middleware;

use App\Application\Http\ApiStatus;
use App\Infrastructure\Http\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

/**
 * Dispatcher
 */
class Dispatcher implements MiddlewareInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \Psr\Http\Message\StreamFactoryInterface
     */
    protected $streamFactory;

    /**
     * @var \Psr\Http\Message\ResponseFactoryInterface
     */
    protected $responseFactory;

    /**
     * @var \App\Infrastructure\Http\Router
     */
    protected $router;

    /**
     * Constructor
     *
     * @param \Psr\Container\ContainerInterface $container Container
     * @param \Psr\Http\Message\ResponseFactoryInterface $responseFactory Response Factory
     * @param \Psr\Http\Message\StreamFactoryInterface $streamFactory Stream Factory
     */
    public function __construct(
        ContainerInterface $container,
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        Router $router
    ) {
        $this->container = $container;
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->router = $router;
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $handlerClass = $this->router->routeRequest($request);

        if ($handlerClass === null) {
            return $handler->handle($request);
        }

        if (!$this->container->has($handlerClass)) {
            return $handler->handle($request);
        }

        $requestHandler = $this->container->get($handlerClass);

        try {
            $result = $requestHandler($request);
        } catch (Throwable $e) {
            $stream = $this->streamFactory->createStream(
                json_encode(
                    [
                        'status' => ApiStatus::ERROR,
                        'message' => $e->getMessage(),
                        'code' => $e->getCode(),
                        'trace' => $e->getTrace()
                    ]
                )
            );

            return $this->responseFactory
                ->createResponse(500)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($stream);
        }

        if ($result instanceof ResponseFactoryInterface) {
            return $result;
        }

        $result = json_encode($result);
        $stream = $this->streamFactory->createStream($result);

        return $this->responseFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);
    }
}
