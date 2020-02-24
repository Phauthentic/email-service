# Installation

Clone the project via [git](https://git-scm.com/) and install dependencies via [Composer](https://getcomposer.org/).

## Application

```sh
git clone git@github.com:Phauthentic/email-service.git
cd email-service
composer install
```

## Dev-Tools

Dev-Tools are managed and installed via [Phive](https://github.com/phar-io/phive).

Assuming that you have a global install of Phive just run this command to install phpunit, phpcs and phpstan.

```php
phive install
```

**Make sure the service is not reachable from the outside of your private network!**

## Configure your mailers

To be done
