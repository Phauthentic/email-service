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
use Phauthentic\Email\EmailAddressCollectionInterface;
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

        $receiver = $this->emailAddressListFromArray($command->receiver());
        $bcc = $this->emailAddressListFromArray($command->bcc());
        $cc = $this->emailAddressListFromArray($command->cc());

        $email = Email::create(
            $sender,
            $receiver,
            $cc,
            $bcc,
            $command->subject(),
            $command->htmlContent(),
            $command->textContent()
        );

        $mailer = MailerRegistry::get($command->mailer());
        $mailer->send($email);
    }

    /**
     * @return EmailAddressCollection
     */
    protected function emailAddressListFromArray(array $emailAddresses): EmailAddressCollectionInterface
    {
        $collection = new EmailAddressCollection();
        foreach ($emailAddresses as $address) {
            if (is_array($address)) {
                $emailAddress = EmailAddress::fromArray($address);
            } else {
                $emailAddress = EmailAddress::create($address);
            }
            $collection->add($emailAddress);
        }

        return $collection;
    }
}
