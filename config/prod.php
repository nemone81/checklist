<?php

// configure your app for the production environment

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
    'host'     => '127.0.0.1',
    'dbname'   => 'cklist',
    'user'     => 'root',
    'password' => '',
);

// twitter credentials
$app['twitter_key'] = 'gzjfLa8lFJAE6DciEwHVrJVJY';
$app['twitter_secret'] = 'sRc5Zr1duSC6HOgOvx8QCbOA4d7GgEjnO6P7ikmmy7MNnAWcy9';