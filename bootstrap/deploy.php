<?php

namespace Deployer;

import('recipe/contao.php');
import('contrib/cachetool.php');

require __DIR__.'/vendor/terminal42/deployer-recipes/recipe/contao-rsync.php';
require __DIR__.'/vendor/terminal42/deployer-recipes/recipe/deploy.php';
require __DIR__.'/vendor/terminal42/deployer-recipes/recipe/encore.php';
// or
require __DIR__.'/vendor/terminal42/deployer-recipes/recipe/gulp.php';

set('keep_releases', 10);

set('rsync_src', __DIR__);

host('www.example.org')
    ->set('remote_user', 'foo')
    ->set('deploy_path', '/var/www/{{remote_user}}/html/{{hostname}}')
    ->set('bin/composer', 'php /var/www/{{remote_user}}/composer.phar')
    ->set('cachetool_args', '--web=SymfonyHttpClient --web-path=./{{public_path}} --web-url=https://{{hostname}}')
;

before('deploy:vendors', 'deploy:platform_release');

before('deploy:publish', 'contao:install:lock');
before('deploy:publish', 'contao:manager:download');
after('contao:manager:download', 'contao:manager:lock');

after('deploy:symlink', 'cachetool:clear:opcache');

after('deploy:failed', 'deploy:unlock');
after('deploy:failed', 'contao:maintenance:disable');
