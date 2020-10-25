<!-- DO NOT EDIT THIS FILE! -->
<!-- Instead edit recipe/common.php -->
<!-- Then run bin/docgen -->

# common

[Source](/recipe/common.php)



* Require
  * [`recipe/deploy/check_remote.php`](/docs/recipe/deploy/check_remote.md)
  * [`recipe/deploy/cleanup.php`](/docs/recipe/deploy/cleanup.md)
  * [`recipe/deploy/clear_paths.php`](/docs/recipe/deploy/clear_paths.md)
  * [`recipe/deploy/copy_dirs.php`](/docs/recipe/deploy/copy_dirs.md)
  * [`recipe/deploy/info.php`](/docs/recipe/deploy/info.md)
  * [`recipe/deploy/lock.php`](/docs/recipe/deploy/lock.md)
  * [`recipe/deploy/push.php`](/docs/recipe/deploy/push.md)
  * [`recipe/deploy/release.php`](/docs/recipe/deploy/release.md)
  * [`recipe/deploy/rollback.php`](/docs/recipe/deploy/rollback.md)
  * [`recipe/deploy/setup.php`](/docs/recipe/deploy/setup.md)
  * [`recipe/deploy/shared.php`](/docs/recipe/deploy/shared.md)
  * [`recipe/deploy/status.php`](/docs/recipe/deploy/status.md)
  * [`recipe/deploy/symlink.php`](/docs/recipe/deploy/symlink.md)
  * [`recipe/deploy/update_code.php`](/docs/recipe/deploy/update_code.md)
  * [`recipe/deploy/vendors.php`](/docs/recipe/deploy/vendors.md)
  * [`recipe/deploy/writable.php`](/docs/recipe/deploy/writable.md)
  * [`recipe/provision/provision.php`](/docs/recipe/provision/provision.md)
* Config
  * [`user`](#user)
  * [`keep_releases`](#keep_releases)
  * [`repository`](#repository)
  * [`shared_dirs`](#shared_dirs)
  * [`shared_files`](#shared_files)
  * [`copy_dirs`](#copy_dirs)
  * [`clear_paths`](#clear_paths)
  * [`clear_use_sudo`](#clear_use_sudo)
  * [`use_relative_symlink`](#use_relative_symlink)
  * [`use_atomic_symlink`](#use_atomic_symlink)
  * [`env`](#env)
  * [`dotenv`](#dotenv)
  * [`current_path`](#current_path)
  * [`sudo_askpass`](#sudo_askpass)
* Tasks
  * [`deploy:prepare`](#deployprepare)
  * [`deploy:publish`](#deploypublish)
  * [`deploy:success`](#deploysuccess)
  * [`deploy:failed`](#deployfailed)
  * [`logs`](#logs) — Follow latest application logs.

## Config
### user
[Source](/recipe/common.php#L31)

Name of current user who is running deploy.
It will be shown in `dep status` command as author.
If not set will try automatically get git user name,
otherwise output of `whoami` command.

### keep_releases
[Source](/recipe/common.php#L48)

Number of releases to preserve in releases folder.

### repository
[Source](/recipe/common.php#L51)

Repository to deploy.

### shared_dirs
[Source](/recipe/common.php#L58)

List of dirs what will be shared between releases.
Each release will have symlink to those dirs stored in {{deploy_path}}/shared dir.
```php
set('shared_dirs', ['storage']);
```

### shared_files
[Source](/recipe/common.php#L65)

List of files what will be shared between releases.
Each release will have symlink to those files stored in {{deploy_path}}/shared dir.
```php
set('shared_files', ['.env']);
```

### copy_dirs
[Source](/recipe/common.php#L69)

List of dirs to copy between releases.
For example you can copy `node_modules` to speedup npm install.

### clear_paths
[Source](/recipe/common.php#L72)

List of paths to remove from [release_path](/docs/recipe/deploy/release.md#release_path).

### clear_use_sudo
[Source](/recipe/common.php#L75)

Use sudo for deploy:clear_path task?

### use_relative_symlink
[Source](/recipe/common.php#L77)



### use_atomic_symlink
[Source](/recipe/common.php#L80)



### env
[Source](/recipe/common.php#L98)

Remote environment variables.
```php
set('env', [
    'KEY' => 'something',
]);
```

It is possible to override it per `run()` call.

```php
run('echo $KEY', ['env' => ['KEY' => 'over']]
```

### dotenv
[Source](/recipe/common.php#L107)

Path to `.env` file which will be used as environment variables for each command per `run()`.

```php
set('dotenv', '[current_path](/docs/recipe/common.md#current_path)/.env');
```

### current_path
[Source](/recipe/common.php#L115)

Return current release path. Default to {{deploy_path}}/`current`.
```php
set('current_path', '/var/public_html');
```

### sudo_askpass
[Source](/recipe/common.php#L135)

Path to a file which will store temp script with sudo password.
Defaults to `.dep/sudo_pass`. This script is only temporary and will be deleted after
sudo command executed.


## Tasks
### deploy:prepare
[Source](/recipe/common.php#L150)



This task is group task which contains next tasks:
* [`deploy:info`](/docs/recipe/deploy/info.md#deployinfo)
* [`deploy:setup`](/docs/recipe/deploy/setup.md#deploysetup)
* [`deploy:lock`](/docs/recipe/deploy/lock.md#deploylock)
* [`deploy:release`](/docs/recipe/deploy/release.md#deployrelease)
* [`deploy:update_code`](/docs/recipe/deploy/update_code.md#deployupdate_code)
* [`deploy:shared`](/docs/recipe/deploy/shared.md#deployshared)
* [`deploy:writable`](/docs/recipe/deploy/writable.md#deploywritable)


### deploy:publish
[Source](/recipe/common.php#L160)



This task is group task which contains next tasks:
* [`deploy:symlink`](/docs/recipe/deploy/symlink.md#deploysymlink)
* [`deploy:unlock`](/docs/recipe/deploy/lock.md#deployunlock)
* [`deploy:cleanup`](/docs/recipe/deploy/cleanup.md#deploycleanup)
* [`deploy:success`](/docs/recipe/common.md#deploysuccess)


### deploy:success
[Source](/recipe/common.php#L170)

Prints success message

### deploy:failed
[Source](/recipe/common.php#L180)

Hook on deploy failure.

### logs
[Source](/recipe/common.php#L189)

Follow latest application logs.
