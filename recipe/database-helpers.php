<?php

namespace Deployer;

use Deployer\Host\Localhost;
use Deployer\Task\Context;

desc('Downloads a database dump from given host and overrides the local database');
task('database:retrieve', static function () {
    $src = get('rsync_src');
    while (is_callable($src)) {
        $src = $src();
    }
    if (!trim($src)) {
        throw new \RuntimeException('You need to specify a source path.');
    }
    $dst = get('rsync_dest');
    while (is_callable($dst)) {
        $dst = $dst();
    }
    if (!trim($dst)) {
        throw new \RuntimeException('You need to specify a destination path.');
    }
    $host = Context::get()->getHost();
    if ($host instanceof Localhost) {
        throw new \RuntimeException('Remote host is localhost.');
    }

    echo 'Preparing backup on remote..';

    run('cd {{release_path}} && {{bin/php}} {{bin/console}} contao:backup:create');
    $dumpFilename = run('ls var/backups | tail -n 1');
    echo ".finished\n";
    runLocally('mkdir -p var/backups');
    echo 'Downloading backup archive..';
    runLocally("scp '{$host->getConnectionString()}:$dst/var/backups/$dumpFilename' '$src/var/backups'");

    echo ".finished\n";
    echo 'Restoring local database....';
    runLocally("php vendor/bin/contao-console contao:backup:restore $dumpFilename");
    echo ".finished\n";
    echo 'Run migration scripts.......';
    try {
        runLocally('php vendor/bin/contao-console contao:migrate --no-interaction --no-backup');
        echo ".finished\n";
    } catch (\Exception $e) {
        echo ".skipped\n";
    }

    echo "  Restore of local database completed\n";
}
);

task('ask_retrieve', static function () {
    if (!askConfirmation('Local database will be overriden. OK?')) {
        die("Restore cancelled.\n");
    }
});

before('database:retrieve', 'ask_retrieve');

desc('Restores the local database on the given host');
task('database:release', static function () {
    $src = get('rsync_src');
    while (is_callable($src)) {
        $src = $src();
    }
    if (!trim($src)) {
        throw new \RuntimeException('You need to specify a source path.');
    }
    $dst = get('rsync_dest');
    while (is_callable($dst)) {
        $dst = $dst();
    }
    if (!trim($dst)) {
        throw new \RuntimeException('You need to specify a destination path.');
    }
    $host = Context::get()->getHost();
    if ($host instanceof Localhost) {
        throw new \RuntimeException('Remote host is localhost.');
    }

    echo 'Preparing local backup.....';

    runLocally('php vendor/bin/contao-console contao:backup:create');
    echo ".finished\n";
    $dumpFilename = runLocally('ls var/backups | tail -n 1');
    echo 'Uploading backup archive...';
    runLocally("scp '$src/backups/$dumpFilename' '{$host->getConnectionString()}:$dst/var/backups/$dumpFilename");

    echo ".finished\n";
    echo 'Restoring remote database..';
    run("cd {{release_path}} && {{bin/php}} {{bin/console}} contao:backup:restore $dumpFilename");
    echo ".finished\n";

    echo "  Restore of remote database completed\n";
});

task('ask_release', static function () {
    if (!askConfirmation('Remote (!) database will be overridden. OK?')) {
        die("Restore cancelled.\n");
    }
});

before('database:release', 'ask_release');
after('database:release', 'contao:migrate');
