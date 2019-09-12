<?php

namespace Deployer;

$recipes = [
    // Base recipes
    'common',
    'symfony',
    'rsync',

    // Custom recipes
    'contao',
    'database',
    'deploy',
    'gulp',
    'maintenance',
];

// Require the recipes
foreach ($recipes as $recipe) {
    require_once sprintf('recipe/%s.php', $recipe);
}

// Load the hosts
inventory('deploy-hosts.yml');

/**
 * ===============================================================
 * Configuration
 *
 * Define the deployment configuration. Each of the variables
 * can be overridden individually per each host.
 * ===============================================================
 */
// Enable SSH multiplexing
set('ssh_multiplexing', true);

// Number of releases to keep
set('keep_releases', 3);

// Disable anonymous stats
set('allow_anonymous_stats', false);

// Rsync
set('rsync_src', __DIR__);
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

/**
 * ===============================================================
 * Tasks
 * ===============================================================
 */
// Main task
task('deploy', [
    // Prepare
    'contao:validate',
    'encore:compile',

    // Deploy
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'rsync',
    'deploy:create_initial_dirs',
    'deploy:shared',
    'deploy:composer_self_update',
    'deploy:platform_release',
    'deploy:vendors',
    'deploy:entry_points',
    'deploy:writable',

    // Release
    'maintenance:enable',
    'deploy:symlink',
    'deploy:clear_accelerator_clear',
    'database:backup',
    'database:migrate',
    'contao:update_database',
    'maintenance:disable',

    // Cleanup
    'deploy:unlock',
    'cleanup',
    'success',
])->desc('Deploy your project');
