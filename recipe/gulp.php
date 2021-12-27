<?php

namespace Deployer;

require_once __DIR__ . '/node.php';

set('gulp_env', 'prod');

add('exclude', [
    'gulpfile.js',
    'gulpfile-config.js',
    'gulpfile-secret.js',

    '/gulp',
]);

desc('Compile assets');
task('gulp:compile', function () {
    runLocally('./node_modules/.bin/gulp --{{gulp_env}}');
});

before('deploy', 'gulp:compile');
