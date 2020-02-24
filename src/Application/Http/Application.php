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

namespace App\Application\Http;

use App\Infrastructure\Http\ResponseEmitterInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Application.
 */
class Application
{
    /**
     * Server Request Factory
     *
     * @var \Psr\Http\Message\RequestFactoryInterface
     */
    protected $requestFactory;

    /**
     * Request Handler / Middleware Stack
     *
     * @var \Psr\Http\Server\RequestHandlerInterface
     */
    protected $requestHandler;

    /**
     * Response Emitter
     *
     * @var object
     */
    protected $responseEmitter;

    /**
     * Constructor.
     */
    public function __construct(
        ServerRequestFactoryInterface $requestFactory,
        RequestHandlerInterface $requestHandler,
        ResponseEmitterInterface $responseEmitter
    ) {
        $this->requestFactory = $requestFactory;
        $this->requestHandler = $requestHandler;
        $this->responseEmitter = $responseEmitter;
    }

    /**
     * Runs the application and sends the data to the client.
     *
     * - Builds the server request object
     * - Builds the middleware stack and runs it
     * - Emits the response to the client
     *
     * @return void
     */
    public function run(): void
    {
        $request = $this->createRequestObject();
        $response = $this->handleRequest($request);
        $this->emitResponse($response);
    }

    /**
     * Calls the middleware stack
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request Request
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        return $this->requestHandler->handle($request);
    }

    /**
     * Emits the response
     *
     * @return void
     */
    protected function emitResponse(ResponseInterface $response): void
    {
        $this->responseEmitter->emit($response);
    }

    /**
     * Creates the server request object
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    protected function createRequestObject(): ServerRequestInterface
    {
        return $this->requestFactory->createServerRequest(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_SERVER
        );
    }
}
