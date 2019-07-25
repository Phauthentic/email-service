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
require_once '../vendor/autoload.php';

/*******************************************************************************
 * Container
 ******************************************************************************/
$container = new \League\Container\Container();
$container->delegate(
    new \League\Container\ReflectionContainer
);

/*******************************************************************************
 * PSR App Stack
 ******************************************************************************/
$container->add(\Psr\Container\ContainerInterface::class, $container);
$container->add(\App\Infrastructure\Http\Router::class, function () {
    return \App\Infrastructure\Http\Router::fromArray([
        'post' => [
            '/emails$/' => \App\Application\Http\RequestHandler\Email\Send::class,
            '/templates$/' => \App\Model\Template\CommandHandler\AddTemplate::class,
        ],
        'put' => [
        ],
        'get' => [
            '/mailers$/' => \App\Application\Http\RequestHandler\Mailers\Listing::class
        ],
        'delete' => [
        ]
    ]);
});
$container->add(\Psr\Http\Message\ResponseFactoryInterface::class, \Tuupola\Http\Factory\ResponseFactory::class);
$container->add(\Psr\Http\Message\StreamFactoryInterface::class, \Tuupola\Http\Factory\StreamFactory::class);
$container->add(\Psr\Http\Message\ServerRequestFactoryInterface::class, \App\Infrastructure\Http\ServerRequestFactory::class);
$container->add(\App\Infrastructure\Http\ResponseEmitterInterface::class, \App\Infrastructure\Http\SapiStreamEmitter::class);
$container->add(\Psr\Http\Server\RequestHandlerInterface::class, new \Moon\HttpMiddleware\Delegate([
    \App\Infrastructure\Http\Middleware\Dispatcher::class,
    \App\Infrastructure\Http\Middleware\NotFoundMiddleware::class
], function() {}, $container));

/*******************************************************************************
 * Mailer
 ******************************************************************************/
$container->add(\Phauthentic\Email\Mailer\SwiftMailer::class, function() {
    $transport = (new \Swift_SmtpTransport('localhost', 1025))
      ->setUsername('')
      ->setPassword('');

    $swiftMailer = new Swift_Mailer($transport);

    return new \Phauthentic\Email\Mailer\SwiftMailer($swiftMailer);
});

$container->add(\Phauthentic\Email\Mailer\MailerInterface::class, \Phauthentic\Email\Mailer\SwiftMailer::class);

\Phauthentic\Email\Mailer\MailerRegistry::add(
    'default',
    $container->get(\Phauthentic\Email\Mailer\SwiftMailer::class)
);

/*******************************************************************************
 * Command Bus
 ******************************************************************************/

// Next we create a new Tactician ContainerLocator, passing in both
// a fully configured container instance and the map.
use \League\Tactician\Container\ContainerLocator;
$containerLocator = new \League\Tactician\Container\ContainerLocator(
    $container,
    [
        \App\Model\Email\Command\SendEmail::class => \App\Model\Email\CommandHandler\SendEmailHandler::class
    ]
);
// Finally, we pass the ContainerLocator into the CommandHandlerMiddleware that
// we use in almost every CommandBus.
$commandHandlerMiddleware = new \League\Tactician\Handler\CommandHandlerMiddleware(
    new \League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor(),
    $containerLocator,
    new \League\Tactician\Handler\MethodNameInflector\HandleInflector()
);
// And that's it! Drop it in our command bus and away you go.
$commandBus = new \League\Tactician\CommandBus(
    [
        $commandHandlerMiddleware
    ]
);
$container->add(\League\Tactician\CommandBus::class, $commandBus);
