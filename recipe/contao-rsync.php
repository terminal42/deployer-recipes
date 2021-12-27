<?php

namespace Deployer;

import('contrib/rsync.php');

set('rsync_dest','{{release_path}}');

set('exclude', [
    '.git',
    '/.github',
    '/.idea',
    '/deploy.php',
    '/.env.local',
    '/.gitignore',
    '/config/parameters.yml',
    '/contao-manager',
    '/tests',
    '/var',
    '/vendor',
    '/app/Resources/contao/config/runonce*',
    '/assets',
    '/files',
    '/system',
    '/web/bundles',
    '/web/assets',
    '/web/files',
    '/web/share',
    '/web/system',
    '/web/app.php',
    '/web/app_dev.php',
    '/web/index.php',
    '/web/preview.php',
    '/web/robots.txt',
]);

set('rsync', function () {
    return [
        'exclude' => array_unique(get('exclude', [])),
        'exclude-file' => false,
        'include' => [],
        'include-file' => false,
        'filter' => [],
        'filter-file' => false,
        'filter-perdir' => false,
        'flags' => 'rz',
        'options' => ['delete'],
        'timeout' => 300,
    ];
});

desc('Use rsync task to pull project files');
task('deploy:update_code', function () {
    invoke('rsync');
});
