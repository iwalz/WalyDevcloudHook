<?php
require_once '../../../autoload.php';
use Gitonomy\Git\Admin;
use Gitonomy\Git\Repository;
system('rm -rf /tmp/hookrepo');
$admin = new Admin();
$repository = $admin->cloneTo(
    '/tmp/hookrepo',
    'https://github.com/iwalz/hookrepo',
    false
);
