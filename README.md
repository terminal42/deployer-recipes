# terminal42 deployer recipes

This repository contains recipes to integrate with [deployer](https://github.com/deployphp/deployer).

## Installing

```
composer require terminal42/deployer-recipes:dev-master@dev --dev
```

## Usage

### Include recipes manually

Include recipes in your `deploy.php` file:

```php
require 'recipe/contao.php';
require 'recipe/database.php';
require 'recipe/deploy.php';
require 'recipe/encore.php'; // or 'recipe/gulp.php';
require 'recipe/maintenance.php';
require 'recipe/symfony.php';
``` 

### Bootstrap file

Copy [`deploy-hosts.yml`](bootstrap/deploy-hosts.yml) to your project root and one of 
the [bootstrap files](bootstrap) as your `deploy.php` file:

1. [`contao4-encore.php`](bootstrap/contao4-encore.php) – Contao 4 setup with Encore for assets management
2. [`contao4-gulp.php`](bootstrap/contao4-gulp.php) – Contao 4 setup with Gulp for assets management

## License

Licensed under the [MIT license](https://github.com/terminal42/deployer-recipes/blob/master/LICENSE).
