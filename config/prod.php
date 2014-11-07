<?php

// configure your app for the production environment
require_once 'constants.php';

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');


$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
	// add custom globals, filters, tags, ...
	
    return $twig;

}));

// Mailer config. See http://silex.sensiolabs.org/doc/providers/swiftmailer.html
$app['swiftmailer.options'] = array();

// Database config. See http://silex.sensiolabs.org/doc/providers/doctrine.html
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'host'     => DB_HOST,
    'dbname'   => DB_NAME,
    'user'     => DB_USER,
    'password' => DB_PWD,
);

// twitter credentials
$app['twitter_key'] = TWITTER_KEY;
$app['twitter_secret'] = TWITTER_SECRET;

$app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
    $types[] = new \Cklst\Form\ListType();
	$types[] = new \Cklst\Form\TodoType();
    return $types;
}));
