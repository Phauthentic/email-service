<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;

$client = new Client(['base_uri' => 'http://127.0.0.1:8081']);

try {
    $response = $client->request('POST', '/send', [
        'form_params' => [
            'sender' => [
                'Developer' => 'developer@code.com'
            ],
            'receiver' => [
                ['Foo' => 'foo@bar.com'],
                ['Bar' => 'boo@bar.com'],
            ],
            'subject' => 'test email',
            'htmlContent' => 'HTML Content',
            'textContent' => 'Text content'
        ]
    ]);

    echo var_export($response->getBody()->read($response->getBody()->getSize()), true);

} catch (ServerException $e) {
    echo $e->getMessage();
}
