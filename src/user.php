<?php


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
        'pattern' => '^.*$',
        'anonymous' => true,
        'oauth' => array(
            //'login_path' => '/auth/{service}',
            //'callback_path' => '/auth/{service}/callback',
            //'check_path' => '/auth/{service}/check',
            'failure_path' => '/login',
            'with_csrf' => true
        ),
		'remember_me' => array(
			'key'                => 'Gt$$cklist$$tG',
			'always_remember_me' => true,
			/* Other options */
		),
        'form' => array(
            'login_path' => '/',
            'check_path' => 'login_check',
        ),
		'logout' => array(
			'logout_path' => '/logout',
			'with_csrf' => true
		),
		'users' => new Cklst\Security\User\Provider\OAuthDbUserProvider($app['db'])
	),
	'list'=> array(
        'pattern' => '^/list$',
		'anonymous' => false,
    ),
);

$app->before(function (Symfony\Component\HttpFoundation\Request $request, Silex\Application $app) {
	
    $services = array_keys($app['oauth.services']);
	$login_paths = array_map(function ($service) use ($app) {
            return $app['url_generator']->generate('_auth_service', array(
				'service' => $service,
                '_csrf_token' => $app['form.csrf_provider']->generateCsrfToken('oauth')
            ));
        }, array_combine($services, $services));
	$logout_path = $app['url_generator']->generate('logout', array(
            '_csrf_token' => $app['form.csrf_provider']->generateCsrfToken('logout')
        ));
		
	$app['twig']->addGlobal('login_paths', $login_paths);
	$app['twig']->addGlobal('logout_path', $logout_path);		
	
	
    $token = $app['security']->getToken();
    $app['user'] = null;
    if ($token && $app['security']->isGranted('IS_AUTHENTICATED_FULLY')) {
        $app['user'] = $token->getUser();
    }
	
});



