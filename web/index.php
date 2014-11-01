<?php
date_default_timezone_set('Europe/Rome');

// PHP 5.4's built in server can now server static files
// @see http://silex.sensiolabs.org/doc/web_servers.html#php-5-4
$filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

ini_set('display_errors', 0);

require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../src/app.php';
require __DIR__.'/../config/prod.php';
require __DIR__.'/../src/user.php';
require __DIR__.'/../src/controllers.php';
$app->run();
