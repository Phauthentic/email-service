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
    protected $mailer;

    /**
     * Creates a new send email command
     *
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
        array $attachments,
        array $headers
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
}
