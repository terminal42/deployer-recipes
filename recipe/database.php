<?php

namespace Deployer;

use Deployer\Exception\RuntimeException;

/**
 * ===============================================================
 * Tasks
 * ===============================================================
 */

// Backup database
task('database:backup', function () {
    try {
        run('cd {{release_path}} && {{bin/composer}} show backup-manager/symfony');
    } catch (RuntimeException $e) {
        writeln("\r\033[1A\033[33C … skipped");

        /** @noinspection PhpUndefinedMethodInspection */
        output()->setWasWritten(false);

        return;
    }

    run('{{bin/php}} {{bin/console}} backup-manager:backup production local {{console_options}}');
})->desc('Backup database');

// Migrate database
task('database:migrate', function () {
    try {
        run('cd {{release_path}} && {{bin/composer}} show doctrine/doctrine-migrations-bundle');
    } catch (RuntimeException $e) {
        writeln("\r\033[1A\033[33C … skipped");

        /** @noinspection PhpUndefinedMethodInspection */
        output()->setWasWritten(false);

        return;
    }

    run('{{bin/php}} {{bin/console}} doctrine:migrations:migrate {{console_options}} --allow-no-migration');
})->desc('Migrate database');
