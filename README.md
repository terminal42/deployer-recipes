# terminal42 deployer recipes

This repository contains recipes to integrate with [Deployer](https://github.com/deployphp/deployer).
These recipes are to extend the official ones. Read the Contao documentation about Deployer before.

## Installing

Version 2.0 of this repository is for use with Contao >=4.13 and Deployer >=7.0.

```
composer require deployer/recipes terminal42/deployer-recipes:^2.0 --dev
```

## Usage

Copy the [deploy.php](bootstrap/deploy.php) to your project root. Adjust the file as needed.

## Pro Tips

### Files sync

This recipe provides a task to easily download the "files" folder from remote.

First, include the `files-sync.php` recipe:

```php
require __DIR__.'/vendor/terminal42/deployer-recipes/recipe/files-sync.php';
```

You can use the command `dep files:retrieve example.com` to sync the remote "files" folder with the local "files" folder.

### Database Helpers (Restore and release)

This collection provides a tasks to easily restore/release the database `dev <-> live` unidirectionally.

First, include the `database-helpers.php` recipe:

```php
require __DIR__.'/vendor/terminal42/deployer-recipes/recipe/database-helpers.php';
```

You can use the command `dep database:retrieve example.com` to download a database dump from remote (example.com) and overwrite the local database.

You can use the command `dep database:release example.com` to overwrite the remote (example.com) database with the local one.

## Further Reading

- https://docs.contao.org/....
- https://deployer.org/docs/

## License

Licensed under the [MIT license](https://github.com/terminal42/deployer-recipes/blob/master/LICENSE).
