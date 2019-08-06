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
namespace App\Model\Email\CommandHandler;

use App\Model\Email\Command\SendEmail;
use League\Tactician\CommandBus;
use Phauthentic\Email\Email;
use Phauthentic\Email\EmailAddress;
use Phauthentic\Email\EmailAddressCollection;
use Phauthentic\Email\HeaderCollection;
use Phauthentic\Email\Mailer\MailerRegistry;

/**
 * Send Email Handler
 */
class SendEmailHandler
{
    /**
     * @param \App\Model\Email\Command\SendEmail
     */
    public function handle(SendEmail $command)
    {
        $sender = $command->sender();
        if (is_array($sender)) {
            $sender = EmailAddress::fromArray($sender);
        } else {
            $sender = EmailAddress::create($sender);
        }

        $email = Email::create(
            $sender,
            EmailAddressCollection::fromArray($command->receiver()),
            EmailAddressCollection::fromArray($command->cc()),
            EmailAddressCollection::fromArray($command->bcc()),
            $command->subject(),
            $command->htmlContent(),
            $command->textContent(),
            $command->priority(),
            HeaderCollection::fromArray($command->headers()),
            $command->options()
        );

        $mailer = MailerRegistry::get($command->mailer());
        $mailer->send($email);
    }
}
