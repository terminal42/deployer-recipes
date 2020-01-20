<?php

namespace Deployer;

use Deployer\Exception\RuntimeException;

/**
 * ===============================================================
 * Configuration
 * ===============================================================
 */

// Environment
set('symfony_env', 'prod');

// Console options
set('console_options', function () {
    return '--no-interaction --env={{symfony_env}}';
});

// Shared files
if (is_file(getcwd() . '/app/config/parameters.yml')) {
    add('shared_files', ['app/config/parameters.yml']);
} elseif (is_file(getcwd() . '/config/parameters.yml')) {
    add('shared_files', ['config/parameters.yml']);
}

// Writable dirs
add('writable_dirs', ['var']);

// Console bin
set('bin/console', function () {
    return '{{release_path}}/vendor/bin/contao-console';
});

// Initial directories
add('initial_dirs', ['assets', 'system', 'var', 'web']);

// Shared directories
set('shared_dirs', [
    'assets/images',
    'contao-manager',
    'files',
    'system/config',
    'templates',
    'var/logs',
    'web/share',
]);

// Exclude files
add('exclude', [
    '/README.md',

    'composer.json~',
    '/phpunit.*',

    '/app/config/parameters.yml',
    '/app/config/parameters.yml.dist',
    '/config/parameters.yml',
    '/config/parameters.yml.dist',
    '/tests',
    '/var',
    '/vendor',

    '/app/Resources/contao/config/runonce*',
    '/assets',
    '/files',
    '/system/modules',
    '/system/themes',
    '/web/bundles',
    '/web/assets',
    '/web/files',
    '/web/share',
    '/web/system',
    '/web/app.php',
    '/web/app_dev.php',
    '/web/index.php',
    '/web/preview.php',
    '/web/robots.txt',
]);

/**
 * ===============================================================
 * Tasks
 * ===============================================================
 */

// Validate local setup
task('contao:validate', function () {
    run('./vendor/bin/contao-console contao:version');
})->desc('Validate local Contao setup')->local();

// Update database
task('contao:update_database', function () {
    // First try native update command (Contao >= 4.9)
    try {
        run('{{bin/php}} {{bin/console}} contao:database:update {{console_options}}');

        return;
    } catch (RuntimeException $e) {}

    // Then try command provided by contao-database-commands-bundle
    try {
        run('cd {{release_path}} && {{bin/composer}} show fuzzyma/contao-database-commands-bundle');
    } catch (RuntimeException $e) {
        writeln("\r\033[1A\033[39C … skipped");

        /** @noinspection PhpUndefinedMethodInspection */
        output()->setWasWritten(false);

        return;
    }

    run('{{bin/php}} {{bin/console}} contao:database:update -d {{console_options}}');
})->desc('Update database');

// Download Contao Manager
task('contao:download_manager', function () {
    run('cd {{release_path}} && curl -LsO https://download.contao.org/contao-manager/stable/contao-manager.phar && mv contao-manager.phar web/contao-manager.phar.php');
})->desc('Download the Contao Manager');

// Lock the Contao Install Tool
task('contao:lock_install_tool', function () {
    run('{{bin/php}} {{bin/console}} contao:install:lock');
})->desc('Lock the Contao Install Tool');
