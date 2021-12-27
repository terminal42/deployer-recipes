<?php

namespace Deployer;

require_once __DIR__ . '/node.php';

set('encore_env', 'prod');

add('exclude', [
    'postcss.config.js',
    'webpack.config.js',
]);

desc('Compile assets');
task('encore:compile', function () {
    runLocally('./node_modules/.bin/encore {{encore_env}}');
});

before('deploy', 'encore:compile');
