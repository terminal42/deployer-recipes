<?php

namespace Deployer;

/**
 * ===============================================================
 * Configuration
 * ===============================================================
 */

// Environment
set('gulp_env', 'prod');

// Exclude files
add('exclude', [
    'gulpfile.js',
    'gulpfile-config.js',
    'gulpfile-secret.js',

    '/gulp',
]);

/**
 * ===============================================================
 * Tasks
 * ===============================================================
 */

// Compile assets
task('gulp:compile', function () {
    run('./node_modules/.bin/gulp --{{gulp_env}}');
})->desc('Compile assets')->local();

