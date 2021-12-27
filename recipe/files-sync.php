<?php

namespace Deployer;

use Deployer\Component\Ssh\Client;
use Deployer\Exception\ConfigurationException;
use Deployer\Host\Localhost;
use Deployer\Task\Context;

// Retrieve files from remote (rsync)
// Configuration: Configure `files_dir` parameter, can be array of string. Defaults to /files
desc('Downloads the files from remote.');
task('files:retrieve', static function () {
    $localRoot = get('rsync_src');
    while (is_callable($localRoot)) {
        $localRoot = $localRoot();
    }
    if (!trim($localRoot)) {
        throw new \RuntimeException('You need to specify a source path.');
    }
    $remoteRoot = get('rsync_dest');
    while (is_callable($remoteRoot)) {
        $remoteRoot = $remoteRoot();
    }
    if (!trim($remoteRoot)) {
        throw new \RuntimeException('You need to specify a destination path.');
    }
    $host = Context::get()->getHost();
    if ($host instanceof Localhost) {
        throw new \RuntimeException('Remote host is localhost.');
    }
    try {
        $dirs = (array)get('files_dir');
    } catch (ConfigurationException $e) {
        $dirs = ['files'];
    }
    $dirs = array_map(static fn($dir) => rtrim(ltrim($dir, '/'), '/'), $dirs);

    $sshArguments = Client::connectionOptionsString($host);

    foreach ($dirs as $dir) {
        writeln('<info>Start syncing "$dir/"</info>');
        runLocally("rsync -avz -e 'ssh $sshArguments' '{$host->getConnectionString()}:$remoteRoot/$dir/' '$localRoot/$dir'");
    }
});

task('ask_retrieve_files', static function () {
    if (!askConfirmation('Remote files will be downloaded (no deletes). OK?')) {
        die("Sync cancelled.\n");
    }
});

before('files:retrieve', 'ask_retrieve_files');
