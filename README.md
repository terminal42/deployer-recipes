# terminal42 deployer recipes

This repository contains recipes to integrate with [deployer](https://github.com/deployphp/deployer).

## Installing

```
composer require deployer/recipes terminal42/deployer-recipes:dev-master@dev --dev
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
``` 

### Bootstrap file

Copy [`deploy-hosts.yml`](bootstrap/deploy-hosts.yml) to your project root and one of 
the [bootstrap files](bootstrap) as your `deploy.php` file:

1. [`contao4-encore.php`](bootstrap/contao4-encore.php) – Contao 4 setup with Encore for assets management
2. [`contao4-gulp.php`](bootstrap/contao4-gulp.php) – Contao 4 setup with Gulp for assets management

## Pro Tips

### Disable releases

If you would like to disable the releases (e.g. for a dev system) you can do it simply by including the recipe:

```php
require 'recipe/disable-releases.php';
``` 

### Contao Manager

Although Contao Manager seems to be redundant if the system can be deployed, you may still want to install it
e.g. for [trakked.io](https://www.trakked.io). To do that, simply add the following task to the list:

```diff
task('deploy', [
    // …
    'maintenance:enable',
+   'contao:download_manager'
    // …
])->desc('Deploy your project');
```

### Database Helpers (Restore and release)

This collection provides a tasks to easily restore/release the database `dev <-> live` unidirectionally.

First, include the `database-helpers.php` recipe.

You can use the command `dep database:retrieve example.com` to download a database dump from remote (example.com) and overwrite the local database.

You can use the command `dep database:release example.com` to overwrite the remote (example.com) datbase with the local one.

## Further Reading

- https://deployer.org/docs/
- https://deployer.org/recipes.html
- https://github.com/eikona-media/deployer-recipes

## License

Licensed under the [MIT license](https://github.com/terminal42/deployer-recipes/blob/master/LICENSE).
