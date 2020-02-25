# Examples

This file contains example code that shows how to send a POST request via Guzzle and Curl to send an email.

```php
<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;

/*******************************************************************************
 * Setting the data up
 ******************************************************************************/
$baseUrl = 'http://127.0.0.1:8081';
$data = [
    'sender' => [
        'Developer' => 'developer@code.com'
    ],
    'receiver' => [
        ['Foo' => 'foo@bar.com'],
        ['Bar' => 'boo@bar.com'],
    ],
    'subject' => 'test email',
    'htmlContent' => '<h1>HTML</h1><p>Content</p>',
    'textContent' => 'Text Content'
];

/*******************************************************************************
 * Guzzle Http based Example
 ******************************************************************************/
try {
    $client = new Client(['base_uri' => $baseUrl]);
    $response = $client->request('POST', '/send', [
        'form_params' => $data
    ]);

    echo var_export($response->getBody()->read($response->getBody()->getSize()), true);
} catch (ServerException $e) {
    echo $e->getMessage() . PHP_EOL;
}

/*******************************************************************************
 * CURL based Example, no dependencies
 ******************************************************************************/
$handler = curl_init();
curl_setopt($handler, CURLOPT_URL, $baseUrl . '/send');
curl_setopt($handler, CURLOPT_POST, 1);
curl_setopt($handler, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($handler);
curl_close($handler);

echo $output. PHP_EOL;
