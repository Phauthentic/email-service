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

namespace App\Infrastructure\Email\AdapterFactory;

use Phauthentic\Email\Mailer\MailerInterface;
use Phauthentic\Email\Mailer\SwiftMailer;
use Swift_Mailer;
use Swift_SmtpTransport;

/**
 * Swift Mailer Factory
 */
class SwiftMailerFactory
{

    /**
     * @var array
     */
    protected static $defaultConfig = [
        'username' => '',
        'password' => '',
        'port' => 1025,
        'host' => 'localhost',
        'encryption' => null,
        'transportClass' => Swift_SmtpTransport::class
    ];

    /**
     *
     */
    public static function buildFromArray(array $config): MailerInterface
    {
        $config = array_merge(self::$defaultConfig, $config);

        $transport = self::transportSwitch($config['transportClass'], $config);
        $swiftMailer = new \Swift_Mailer($transport);

        return new SwiftMailer($swiftMailer);
    }

    /**
     *
     */
    protected static function transportSwitch($transport, $config)
    {
        switch ($transport) {
            case Swift_SmtpTransport::class:
                return self::buildSmtpTransport($config);
                break;
        }

        throw new \RuntimeException('Could not build a transport object');
    }

    /**
     *
     */
    protected static function buildSmtpTransport($config)
    {
        return (new Swift_SmtpTransport($config['host'], (int)$config['port'], $config['encryption']))
            ->setUsername($config['username'])
            ->setPassword($config['password']);
    }
}
