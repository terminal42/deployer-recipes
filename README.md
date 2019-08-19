# terminal42 deployer recipes

This repository contains recipes to integrate with [deployer](https://github.com/deployphp/deployer).

## Installing

```
composer require terminal42/deployer-recipes --dev
```

## Usage

### Include recipes manually

Include recipes in your `deploy.php` file:

```php
require 'recipe/contao.php';
require 'recipe/database.php';
require 'recipe/deploy.php';
require 'recipe/gulp.php';
require 'recipe/maintenance.php';
require 'recipe/node.php';
require 'recipe/symfony.php';
``` 

### Bootstrap file

Copy one of the [bootstrap files](bootstrap) to your `deploy.php` file.

## License

Licensed under the [MIT license](https://github.com/terminal42/deployer-recipes/blob/master/LICENSE).
