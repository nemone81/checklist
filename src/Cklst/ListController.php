<?php

namespace Cklst;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Silex\Application;
use Silex\ControllerProviderInterface;

use Cklst\Domain\ListMapper;
use Cklst\Domain\TodoMapper;

class ListController implements ControllerProviderInterface
{
	
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];
		$controllers->before($app["mustBeLogged"]);
		
		$listMapper = new ListMapper($app['db']);
		$todoMapper = new TodoMapper($app['db']);
		
        $controllers->get('/', function (Application $app) use ($listMapper) {
			
			$lists = $listMapper->findAllByUser($app['session']->get('user_id'));
			
			$form = $app['form.factory']->create('list', []);                 
			
            return $app['twig']->render('list/index.html', ['lists'=>$lists, 'form' => $form->createView() ]);
			
        })->bind('list_home');


        $controllers->post('/create', function (Request $request, Application $app) use ($listMapper) {
			
			$form = $app['form.factory']->create('list', []);               
		    $form->handleRequest($request);

		    if ($form->isValid()) {
		        $data = $form->getData();
				
				$listId = $listMapper->create($data['title'], $app['session']->get('user_id'));
				$list = $listMapper->find($listId);
				
				//return new JsonResponse($list);
		        // redirect somewhere
		        return $app->redirect($app['url_generator']->generate('list_home'));
		    }			
			
            //return $app['twig']->render('list/create.html', ['form' => $form->createView()]);
			
        })->bind('list_create');
		

        $controllers->match('/{listId}', function (Request $request, Application $app, $listId) use ($listMapper, $todoMapper) {
			
			$listMapper = new ListMapper($app['db']);
			$list = $listMapper->find($listId);
			$todos = $todoMapper->findAllByList($listId);
			
			$form = $app['form.factory']->create('todo', ['list_id' => $listId]);  
						
			return $app['twig']->render('list/edit.html', ['list'=>$list, 'form' => $form->createView(), 'todos' => $todos]);
			
        })->bind('list_edit');



        return $controllers;
		
    }
}


