<?php

namespace Cklst\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Silex\Application;
use Silex\ControllerProviderInterface;


class TodoController implements ControllerProviderInterface
{
	
	private $app;
	
    public function connect(Application $app)
    {
		$this->app = $app;
		
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];
		$controllers->before($app["mustBeLogged"]);
		
		// todos routing
		$controllers->match('/create', array($this, 'create'))->bind('todo_create')->method('POST');
		$controllers->match('/{todoId}/update', array($this, 'update'))->bind('todo_update')->method('POST|PUT|PATCH');
		$controllers->match('/{todoId}/delete', array($this, 'delete'))->bind('todo_delete')->method('POST|DELETE');

        return $controllers;
		
    }
	
	public function create() 
	{
		$form = $this->app['form.factory']->create('todo', []);                 
	    $form->handleRequest($this->app['request']);

	    if ($form->isValid()) {
	        $data = $form->getData();
			
			$todoId = $this->app['cklist.mapper.todo']->create($data['title'], $data['list_id']);
			$todo = $this->app['cklist.mapper.todo']->find($todoId);

			return new JsonResponse($todo);

	    }
		return new JsonResponse(array());
	}
	
	public function update($todoId)
	{
		return new JsonResponse($this->app['cklist.mapper.todo']->update($todoId));
	}
	
	public function delete($todoId) 
	{
		return new JsonResponse($this->app['cklist.mapper.todo']->delete($todoId));
	}
	
	
}


