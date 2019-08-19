<?php

namespace Deployer;

/**
 * ===============================================================
 * Tasks
 * ===============================================================
 */

// Enable maintenance mode
task('maintenance:enable', function () {
    run('{{bin/php}} {{bin/console}} lexik:maintenance:lock {{console_options}}');
})->desc('Enable maintenance mode');

// Disable maintenance mode
task('maintenance:disable', function () {
    run('{{bin/php}} {{bin/console}} lexik:maintenance:unlock {{console_options}}');
})->desc('Disable maintenance mode');
