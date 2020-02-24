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
namespace App\Infrastructure\CommandBus;

use League\Tactician\CommandBus;
use Psr\Http\Message\ResponseInterface;

/**
 * Command Bus Interface
 *
 * There is no PSR or similar standard API for dispatching a command
 * so this interface is used and can be implemented by a decorator around
 * an external command bus interface.
 */
class TacticianCommandBus extends CommandBus implements CommandBusInterface
{

}
