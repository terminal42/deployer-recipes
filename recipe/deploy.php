<?php

namespace Deployer;

use Deployer\Exception\ConfigurationException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Yaml\Yaml;

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

    '/.dependabot',
    '/.github',

    'deploy.php',
    'deploy-hosts.yml',
]);

desc('Composer self-update');
task('deploy:composer_self_update', function () {
    run('cd {{release_path}} && {{bin/composer}} self-update');
});

desc('Platform release (update version in parameters.yml)');
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
});

desc('Update entry points');
task('deploy:entry_points', function () {
    try {
        if ($htaccess = get('htaccess_filename')) {
            run('cd {{public_path}} && if [ -f "./'.$htaccess.'" ]; then mv ./'.$htaccess.' ./.htaccess; fi');
            run('cd {{public_path}} && rm -f .htaccess_*');
            return;
        }
    } catch (ConfigurationException $e) {}

    switch (get('symfony_env')) {
        case 'prod':
            run('cd {{public_path}} && if [ -f "./.htaccess_production" ]; then mv ./.htaccess_production ./.htaccess; fi');
            run('cd {{public_path}} && if [ -f "./.htaccess_development" ]; then rm -rf ./.htaccess_development; fi');
            break;
        case 'dev':
            run('cd {{public_path}} && if [ -f "./.htaccess_development" ]; then mv ./.htaccess_development ./.htaccess; fi');
            run('cd {{public_path}} && if [ -f "./.htaccess_production" ]; then rm -rf ./.htaccess_production; fi');
            break;
    }
});
