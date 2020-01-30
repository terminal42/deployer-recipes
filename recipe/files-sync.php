<?php

namespace Deployer;

use Deployer\Exception\ConfigurationException;
use Deployer\Exception\RuntimeException;

/**
 * ===============================================================
 * Tasks
 * ===============================================================
 */

// Retrieve files from remote (rsync)
// Configuration: Configure `files_dir` parameter, can be array of string. Defaults to /files
task(
    'files:retrieve',
    static function () {
        $localRoot = get('rsync_src');
        while (is_callable($localRoot)) {
            $localRoot = $localRoot();
        }
        if (!trim($localRoot)) {
            throw new RuntimeException('You need to specify a source path.');
        }
        $remoteRoot = get('rsync_dest');
        while (is_callable($remoteRoot)) {
            $remoteRoot = $remoteRoot();
        }
        if (!trim($remoteRoot)) {
            throw new RuntimeException('You need to specify a destination path.');
        }
        $server = \Deployer\Task\Context::get()->getHost();
        if ($server instanceof \Deployer\Host\Localhost) {
            throw new RuntimeException('Remote host is localhost.');
        }
        try {
            $dirs = (array) get('files_dir');
        } catch (ConfigurationException $e) {
            $dirs = ['files'];
        }
        $dirs = array_map(
            static function ($dir) {
                return rtrim(ltrim($dir, '/'), '/');
            },
            $dirs
        );
        $host = $server->getRealHostname();
        $port = $server->getPort() ? ' -p ' . $server->getPort() : '';
        $user = !$server->getUser() ? '' : $server->getUser() . '@';
        foreach ($dirs as $dir) {
            writeln("<info>Start syncing \"$dir/\"</info>");
            runLocally("rsync$port -avz $user$host:$remoteRoot/$dir/ $localRoot/$dir");
        }
    }
)->desc('Downloads the files from remote.');

task(
    'ask_retrieve_files',
    static function () {
        if (!askConfirmation('Remote files will be downloaded (no deletes). OK?')) {
            die("Sync cancelled.\n");
        }
    }
);

before('files:retrieve', 'ask_retrieve_files');
