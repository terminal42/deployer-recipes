<?php

namespace Deployer;

require_once __DIR__ . '/node.php';

/**
 * ===============================================================
 * Configuration
 * ===============================================================
 */

// Environment
set('encore_env', 'prod');

// Exclude files
add('exclude', [
    'postcss.config.js',
    'webpack.config.js',
]);

/**
 * ===============================================================
 * Tasks
 * ===============================================================
 */

// Compile assets
task('encore:compile', function () {
    runLocally('./node_modules/.bin/encore {{encore_env}}');
})->desc('Compile assets');

