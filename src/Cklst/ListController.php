<?php

namespace Cklst;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Silex\Application;
use Silex\ControllerProviderInterface;

class ListController implements ControllerProviderInterface
{
	
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            return $app['twig']->render('list/index.html', array());
        });

        return $controllers;
    }
}

