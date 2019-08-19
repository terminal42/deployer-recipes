<?php

namespace Deployer;

/**
 * ===============================================================
 * Configuration
 * ===============================================================
 */

// Exclude files
add('exclude', [
    '.babelrc',
    '.eslintrc.json',
    'package.json',
    'package-lock.json',
    'yarn.lock',

    '/layout',
    '/node_modules',
]);
