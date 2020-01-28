<?php

namespace Deployer;

use Deployer\Exception\ConfigurationException;
use Deployer\Exception\RuntimeException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Yaml\Yaml;

/**
 * ===============================================================
 * Configuration
 * ===============================================================
 */

// Exclude files
add('exclude', [
    '._*',
    '.DS_Store',
    '.editorconfig',
    '.env.local',
    '.git',
    '.gitignore',
    '.gitlab-ci.yml',
    '.idea',
    '.php_cs',
    '.php_cs.cache',
    '.circleci',
    'deploy.php',
    'deploy-hosts.yml',
]);

/**
 * ===============================================================
 * Tasks
 * ===============================================================
 */

// Update the Composer
task('deploy:composer_self_update', function () {
    run('cd {{release_path}} && {{bin/composer}} self-update');
})->desc('Composer self-update');

// Platform release (update version in parameters.yml)
task('deploy:platform_release', function () {
    try {
        $version = runLocally('git describe --tags --always');
    } catch (ProcessFailedException $e) {
        // If not successful, maybe there's no branch yet so we try getting the branch name
        $version = runLocally('git rev-parse --abbrev-ref HEAD');
    }

    $version = trim($version);

    if (!$version) {
        throw new \RuntimeException('Unable to get the release version');
    }

    if (is_file(getcwd() . '/app/config/parameters.yml')) {
        $parametersFile = 'app/config/parameters.yml';
    } elseif (is_file(getcwd() . '/config/parameters.yml')) {
        $parametersFile = 'config/parameters.yml';
    } else {
        throw new \RuntimeException('Unable to find the location of parameters.yml file.');
    }

    $params = Yaml::parse(run('cat {{deploy_path}}/shared/' . $parametersFile));
    $params['parameters']['platform_version'] = $version;

    run(sprintf('echo %s > {{deploy_path}}/shared/' . $parametersFile, escapeshellarg(Yaml::dump($params))));
})->desc('Platform release (update version in parameters.yml)');

// Create initial directories task
task('deploy:create_initial_dirs', function () {
    foreach (get('initial_dirs') as $dir) {
        // Set dir variable
        set('_dir', '{{release_path}}/' . $dir);

        // Create dir if it does not exist
        run('if [ ! -d "{{_dir}}" ]; then mkdir -p {{_dir}}; fi');

        // Set rights
        run("chmod -R g+w {{_dir}}");
    }
})->desc('Create initial dirs');

// Update entry points depending on the environment
task('deploy:entry_points', function () {
    try {
        if ($htaccess = get('htaccess_filename')) {
            run('cd {{release_path}}/web && if [ -f "./'.$htaccess.'" ]; then mv ./'.$htaccess.' ./.htaccess; fi');
            run('cd {{release_path}}/web && rm .htaccess_*');
            return;
        }
    } catch (ConfigurationException $e) {}

    switch (get('symfony_env')) {
        case 'prod':
            run('cd {{release_path}}/web && if [ -f "./.htaccess_production" ]; then mv ./.htaccess_production ./.htaccess; fi');
            run('cd {{release_path}}/web && if [ -f "./.htaccess_development" ]; then rm -rf ./.htaccess_development; fi');
            break;
        case 'dev':
            run('cd {{release_path}}/web && if [ -f "./.htaccess_development" ]; then mv ./.htaccess_development ./.htaccess; fi');
            run('cd {{release_path}}/web && if [ -f "./.htaccess_production" ]; then rm -rf ./.htaccess_production; fi');
            break;
    }
})->desc('Update entry points');

// Cache accelerator cache
task('deploy:clear_accelerator_clear', function () {
    try {
        run('cd {{release_path}} && {{bin/composer}} show smart-core/accelerator-cache-bundle');
    } catch (RuntimeException $e) {
        writeln("\r\033[1A\033[40C … skipped");

        /** @noinspection PhpUndefinedMethodInspection */
        output()->setWasWritten(false);

        return;
    }

    run('{{bin/php}} {{bin/console}} cache:accelerator:clear {{console_options}}');
})->desc('Clear accelerator cache');


// Rest the OPcache (tasks deploy:clear_accelerator_clear and deploy:opcache_reset are interchangeable)
task(
    'deploy:opcache_reset',
    static function () {
        run(
            'cd {{current_path}} && echo "<?php opcache_reset();" > web/opcache.php && curl -L https://{{hostname}}/opcache.php && rm web/opcache.php'
        );
    }
)->desc('Clear OPCache');
