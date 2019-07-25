# Mailers

Mailers are take the email instance and take care of sending it or doing something else with them, depending on the mailer.

The recommended mailer is the **Swift Mailer**. If one of the adapters is missing a feature or has a problem please let us know.

## Swift Mailer

Adapter for the [Swift Mailer](https://github.com/swiftmailer/swiftmailer) library.

```php
$transport = (new \Swift_SmtpTransport('localhost', 1025))
  ->setUsername('username')
  ->setPassword('password');

$swiftMailer = new \Swift_Mailer($transport);

$mailer = new \Phauthentic\Email\Mailer\SwiftMailer($swiftMailer);
```

## Log Mailer

Takes a PSR Logger instance to log the email

```php
$logger = new PsrCompatibleLogger();
$mailer = new LogMailer($logger);
```

## Callback Mailer

Takes a callback that gets an email instance passed. You need to implement your sending logic in the callback and return bool.

```php
$callback  = function(EmailInterface $email) {
    // Your logic goes here
    return true;
}
$mailer = new CallbackMailer($callback);
```

## Null Mailer

It actually doesn't do anything, can be used for testing.

```php
$mailer = new NullMailer();
```
