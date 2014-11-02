<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html',
        'errors/'.substr($code, 0, 2).'x.html',
        'errors/'.substr($code, 0, 1).'xx.html',
        'errors/default.html',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});

$app->get('/', function () use ($app) {
	
	$lists = $app['cklist.mapper.list']->findAllWithUserInfo();
	return $app['twig']->render('index.html', [ 'lists' => $lists ] );
})
->bind('home');

$app->get('/login', function () use ($app) {
	return $app['twig']->render('login.html', [] );
})
->bind('login');

$app->match('/logout', function () {})->bind('logout');

// mount controllers
$app->mount('/list', new Cklst\Controller\ListController());
$app->mount('/todo', new Cklst\Controller\TodoController());