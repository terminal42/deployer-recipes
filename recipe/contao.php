<?php

namespace Deployer;

use Deployer\Exception\RuntimeException;

require_once __DIR__ . '/symfony.php';

/**
 * ===============================================================
 * Configuration
 * ===============================================================
 */

// Console bin
set('bin/console', function () {
    return '{{release_path}}/vendor/bin/contao-console';
});

// Initial directories
add('initial_dirs', ['assets', 'system', 'var', 'web']);

// Shared directories
add('shared_dirs', [
    'assets/images',
    'files',
    'system/config',
    'templates',
    'var/logs',
    'web/share',
]);

// Exclude files
add('exclude', [
    '/README.md',

    '/app/Resources/contao/config/runonce*',
    '/assets',
    '/files',
    '/system/modules',
    '/system/themes',
    '/web/assets',
    '/web/files',
    '/web/share',
    '/web/system',
    '/web/index.php',
    '/web/favicon.ico',
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
    try {
        run('cd {{release_path}} && {{bin/composer}} show fuzzyma/contao-database-commands-bundle');
    } catch (RuntimeException $e) {
        writeln("\r\033[1A\033[33C … skipped");

        /** @noinspection PhpUndefinedMethodInspection */
        output()->setWasWritten(false);

        return;
    }

    run('{{bin/php}} {{bin/console}} contao:database:update -d {{console_options}}');
})->desc('Update database');

