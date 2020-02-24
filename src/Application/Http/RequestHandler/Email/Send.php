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

namespace App\Application\Http\RequestHandler\Email;

use App\Model\Email\Command\SendEmail;
use League\Tactician\CommandBus;
use Phauthentic\Email\Priority;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Send Handler
 */
class Send
{
    /**
     * Constructor
     *
     * @param \League\Tactician\CommandBus $commandBus Command Bus
     * @param \Phauthentic\Email\Mailer\SwiftMailer $mailer Mailer
     */
    public function __construct(
        CommandBus $commandBus
    ) {
        $this->bus = $commandBus;
    }

    /**
     * Invoke
     *
     * @param \Psr\Http\Message\ServerRequestInterface
     * @return array
     */
    public function __invoke(ServerRequestInterface $request): array
    {
        $defaults = [
            'mailer' => 'default',
            'sender' => '',
            'subject' => '',
            'receiver' => [],
            'bcc' => [],
            'cc' => [],
            'htmlContent' => '',
            'textContent' => '',
            'attachments' => [],
            'priority' => Priority::NORMAL,
            'headers' => [],
        ];

        $body = array_merge($defaults, (array)$request->getParsedBody());

        $this->bus->handle(
            SendEmail::create(
                $body['mailer'],
                $body['sender'],
                $body['receiver'],
                $body['cc'],
                $body['bcc'],
                $body['subject'],
                $body['htmlContent'],
                $body['textContent'],
                $body['priority'],
                (array)$request->getUploadedFiles(),
                (array)$body['headers']
            )
        );

        return [
            'status' => 'success'
        ];
    }
}
