<?php

// twitter credentials
$app['twitter_key'] = 'gzjfLa8lFJAE6DciEwHVrJVJY';
$app['twitter_secret'] = 'sRc5Zr1duSC6HOgOvx8QCbOA4d7GgEjnO6P7ikmmy7MNnAWcy9';

$app['oauth.services'] = array(
	'twitter' => array(
		'key'    => $app['twitter_key'],
		'secret' => $app['twitter_secret'],
		'scope' => array(),
		'user_endpoint' => 'https://api.twitter.com/1.1/account/verify_credentials.json'
	)
);
$app['security.access_rules'] = array(
	array('^/auth', 'ROLE_USER')
);
$app['security.firewalls'] = array(
	'default' => array(
		'pattern' => '^/',
		'anonymous' => true,
		'oauth' => array(
			//'login_path' => '/auth/{service}',
			//'callback_path' => '/auth/{service}/callback',
			//'check_path' => '/auth/{service}/check',
			'failure_path' => '/',
			'with_csrf' => true
		),
		'remember_me' => array(
			'key'                => 'Gt$$cklist$$tG',
			'always_remember_me' => true,
			/* Other options */
		),
		'logout' => array(
			'logout_path' => '/logout',
			'with_csrf' => true
		),
		'users' => new Cklst\Security\User\Provider\OAuthDbUserProvider($app['db'])
	)
);

$app->before(function (Symfony\Component\HttpFoundation\Request $request) use ($app) {
    $token = $app['security']->getToken();
    $app['user'] = null;

    if ($token && !$app['security.trust_resolver']->isAnonymous($token)) {
        $app['user'] = $token->getUser();
    }
});

