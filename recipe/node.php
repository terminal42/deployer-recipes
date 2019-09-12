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
    '.stylelintrc',
    'package.json',
    'package-lock.json',
    'yarn.lock',

    '/layout',
    '/node_modules',
]);
