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

namespace App\Model\Email\Command;

use Phauthentic\Email\Priority;

/**
 * Send Email Command
 */
class SendEmail
{
    protected $sender;
    protected $subject = '';
    protected $receiver = [];
    protected $cc = [];
    protected $bcc = [];
    protected $htmlContent = null;
    protected $textContent = null;
    protected $mailer = 'default';
    protected $headers = [];
    protected $options = [];
    protected $priority = Priority::NORMAL;
    protected $attachments = [];

    /**
     * Creates a new send email command
     *
     * @param string $mailer Mailer config
     * @param string|array $sender Sender
     * @param array Receiver(s)
     * @param array $bcc BCC
     * @param array $cc CC
     * @param string $subject Subject
     * @param string $htmlContent HTML
     * @param string $textContent Text
     * @param int $priority Priority
     * @param array $attachments Attachments
     * @param array $headers Headers
     * @param array $options Options
     * @return $this
     */
    public static function create(
        string $mailer,
        $sender,
        array $receiver,
        array $cc,
        array $bcc,
        string $subject,
        string $htmlContent,
        string $textContent,
        int $priority,
        array $attachments = [],
        array $headers = [],
        array $options = []
    ) {
        $command = new self();
        $command->mailer = $mailer;
        $command->subject = $subject;
        $command->sender = $sender;
        $command->receiver = $receiver;
        $command->bcc = $bcc;
        $command->cc = $cc;
        $command->htmlContent = $htmlContent;
        $command->textContent = $textContent;
        $command->attachments = $attachments;
        $command->headers = $headers;
        $command->options = $options;
        $command->priority = $priority;

        return $command;
    }

    /**
     * @return string
     */
    public function mailer()
    {
        return $this->mailer;
    }

    /**
     * @return string|array
     */
    public function receiver()
    {
        return $this->receiver;
    }

    /**
     * @return string
     */
    public function htmlContent(): ?string
    {
        return $this->htmlContent;
    }

    /**
     * @return string
     */
    public function textContent(): ?string
    {
        return $this->textContent;
    }

    /**
     * @return string
     */
    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * string|array
     */
    public function sender()
    {
        return $this->sender;
    }

    /**
     * @return array
     */
    public function bcc(): array
    {
        return $this->bcc;
    }

    /**
     * @return array
     */
    public function cc(): array
    {
        return $this->cc;
    }

    /**
     * @return array
     */
    public function priority(): int
    {
        return $this->priority;
    }

    /**
     * @return array
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }
}
