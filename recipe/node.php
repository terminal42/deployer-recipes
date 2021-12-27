<?php

namespace Deployer;

add('exclude', [
    '.babelrc',
    '.eslintrc.json',
    '.stylelintrc',
    'package.json',
    'package-lock.json',
    'yarn.lock',

    '/layout',
    '/node_modules',
]);
