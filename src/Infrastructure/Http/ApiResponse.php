<?php
declare(strict_types=1);
/**
 * Copyright (c) Florian Krämer (https://florian-kraemer.net)
 * Licensed under The GPL3 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Florian Krämer (https://florian-kraemer.net)
 * @author        Florian Krämer
 * @link          https://github.com/Phauthentic
 * @license       https://opensource.org/licenses/GPL-3.0 GPL3 License
 */
namespace App\Infrastructure\Http;

use Psr\Http\Message\ServerRequestInterface;

/**
 * A primitive router, we don't need a full fledged router for this project
 *
 * All it does it matches a requests path via regex and the regex is mapped to
 * a handler class that is a string
 */
class ApiResponse implements \JsonSerializable
{
    public static function create(
        string $status,
        $data,
        ?string $message
    ) {
        $self = new self();
        $self->message = $message;
        $self->data = $data;
        $self->status = $status;
    }

    public function jsonSerialize() {
        return [
            'status' => $this->status,
            'data' => $this->data,
            'message' => $this->message
        ];
    }
}
