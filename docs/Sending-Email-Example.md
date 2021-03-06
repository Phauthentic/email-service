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

} catch (\GuzzleHttp\Exception\ServerException $e) {
    echo $e->getMessage();
}
```

Then run it
```sh
php send.php
```

If everything was correctly configured you should now receive an email.
