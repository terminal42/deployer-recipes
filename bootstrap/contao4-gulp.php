<?php

namespace Deployer;

$recipes = [
    'common',
    'symfony4',

    'deployer/recipes/recipe/rsync',

    'terminal42/deployer-recipes/recipe/contao',
    'terminal42/deployer-recipes/recipe/database',
    'terminal42/deployer-recipes/recipe/deploy',
    'terminal42/deployer-recipes/recipe/gulp',
    'terminal42/deployer-recipes/recipe/maintenance',
];

// Require the recipes
foreach ($recipes as $recipe) {
    if (false === strpos($recipe, '/')) {
        require_once sprintf('recipe/%s.php', $recipe);
        continue;
    }

    require_once sprintf('%s/vendor/%s.php', getcwd(), $recipe);
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

// Recommended when using the database:backup task
add('shared_dirs', ['backups']);

/**
 * ===============================================================
 * Tasks
 * ===============================================================
 */
// Main task
task('deploy', [
    // Prepare
    'contao:validate',
    'gulp:compile',

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
    'deploy:cache_accelerator_clear',
    'database:backup',
    'contao:migrate',
    'maintenance:disable',

    // Cleanup
    'deploy:unlock',
    'cleanup',
    'success',
])->desc('Deploy your project');
