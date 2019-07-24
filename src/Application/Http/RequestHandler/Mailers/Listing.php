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
namespace App\Application\Http\RequestHandler\Mailers;

use App\Model\Email\Command\SendEmail;
use League\Tactician\CommandBus;
use Phauthentic\Email\Email;
use Phauthentic\Email\Mailer\MailerRegistry;
use Phauthentic\Email\Mailer\SwiftMailer;
use Phauthentic\Email\Priority;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Listing
 */
class Listing
{
    /**
     * @return array
     */
    public function __invoke(ServerRequestInterface $request): array
    {
        return MailerRegistry::getMap();
    }
}
