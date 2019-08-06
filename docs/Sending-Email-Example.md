# Sending Email Example

It is recommended to use [MailHog](https://github.com/mailhog/MailHog) for testing.

Setting a new client script up:

```sh
mkdir mail-client
cd mail-client
composer init
composer require guzzlehttp/guzzle
touch send.php
```

Add this php code to the `send.php` file:

```php
<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

$client = new Client([
    // Make sure you configure the right endpoint here!
    'base_uri' => 'http://email-service.test'
]);

try {
    $response = $client->request('POST', '/emails', [
        'form_params' => [
            'sender' => [
                'Developer' => 'developer@code.com'
            ],
            // Change them to whatever you need
            'receiver' => [
                ['Foo' => 'foo@bar.com'],
                ['Bar' => 'boo@bar.com'],
            ],
            'subject' => 'test email',
            'htmlContent' => '<h1>HTML<H1> <p>Content</p>',
            'textContent' => 'Text content'
        ]
    ]);

    echo var_export($response->getBody()->read($response->getBody()->getSize()), true);

} catch (\GuzzleHttp\Exception\ServerException $e) {
    echo $e->getMessage();
}
```

Then run it
```sh
php send.php
```

If everything was correctly configured you should now receive an email.
