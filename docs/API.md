# API

The API is a RESTful API that takes HTTP calls and will respond with JSON.

## Emails

### GET /mailers

Returns a list of mailer profiles that can be used.

### POST /send

 * **string** mailer
 * **string** subject
 * **string** textContent
 * **string** htmlContent
 * **array** sender
 * **array** receiver
 * **array** bcc
 * **array** cc
 * **array** attachments
 * **array** headers
 * **array** options

```php
[
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
```
