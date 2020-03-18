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
        run('cd {{release_path}} && {{bin/composer}} show richardhj/contao-backup-manager');
    } catch (RuntimeException $e) {
        writeln("\r\033[1A\033[32C … skipped");

        /** @noinspection PhpUndefinedMethodInspection */
        output()->setWasWritten(false);

        return;
    }

        run(sprintf('{{bin/php}} {{bin/console}} backup-manager:backup contao local -c gzip --filename %s.sql', date('Y-m-d-H-i-s')));
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
