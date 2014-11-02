<?php

namespace Cklst;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Silex\Application;
use Silex\ControllerProviderInterface;

use Cklst\Domain\TodoMapper;

class TodoController implements ControllerProviderInterface
{
	
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];
		$controllers->before($app["mustBeLogged"]);
		
		$todoMapper = new TodoMapper($app['db']);
		

        $controllers->post('/create', function (Request $request, Application $app) use ($todoMapper) {
			
			$form = $app['form.factory']->create('todo', []);                 
		    $form->handleRequest($request);

		    if ($form->isValid()) {
		        $data = $form->getData();
				
				$todoId = $todoMapper->create($data['title'], $data['list_id']);
				$todo = $todoMapper->find($todoId);

				return new JsonResponse($todo);

		    }
			return new JsonResponse(array());			
						
        })->bind('todo_create');


        $controllers->match('/{todoId}/update', function (Request $request, Application $app, $todoId) use ($todoMapper) {
			        
	        return new JsonResponse($todoMapper->update($todoId));
						
        })->bind('todo_update')->method('POST|PUT|PATCH');		

        $controllers->match('/{todoId}/delete', function (Request $request, Application $app, $todoId) use ($todoMapper) {
			        
	        return new JsonResponse($todoMapper->delete($todoId));
						
        })->bind('todo_delete')->method('POST|DELETE');

        return $controllers;
		
    }
}


