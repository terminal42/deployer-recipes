<?php

namespace Deployer;

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
add('shared_files', ['app/config/parameters.yml']);

// Writable dirs
add('writable_dirs', ['var']);

// Exclude files
add('exclude', [
    '.env.local',
    'composer.json~',
    '/phpunit.*',

    '/app/config/parameters.yml',
    '/app/config/parameters.yml.dist',
    '/config/parameters.yml',
    '/config/parameters.yml.dist',
    '/tests',
    '/var',
    '/vendor',
    '/web/bundles',
    '/web/app.php',
    '/web/app_dev.php',
]);
