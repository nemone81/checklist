<?php

namespace Cklst\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Silex\Application;
use Silex\ControllerProviderInterface;


class ListController implements ControllerProviderInterface
{
	
	private $app;
	
    public function connect(Application $app)
    {
		$this->app = $app;
		
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];
		$controllers->before($app["mustBeLogged"]);
			
		// list routing
		$controllers->match('/', array($this, 'home'))->bind('list_home')->method('GET');
		$controllers->match('/create', array($this, 'create'))->bind('list_create')->method('POST');
		$controllers->match('/{listId}', array($this, 'edit'))->bind('list_edit')->method('GET');
		
		
        return $controllers;
		
    }
	
	
	public function home()
	{
		$lists = $this->app['cklist.mapper.list']->findAllByUser($this->app['session']->get('user_id'));
		$form  = $this->app['form.factory']->create('list', []);                 
		
        return $this->app['twig']->render('list/index.html', [ 'lists'=>$lists, 'form' => $form->createView() ]);
	}

	public function create()
	{
		$form = $this->app['form.factory']->create('list', []);               
	    $form->handleRequest($this->app['request']);

	    if ($form->isValid()) {
			
	        $data = $form->getData();
			$listId = $this->app['cklist.mapper.list']->create($data['title'], $this->app['session']->get('user_id'));
			//$list = $app['cklist.mapper.list']->find($listId);
			//return new JsonResponse($list);
			
	        // redirect to home
	        return $this->app->redirect($this->app['url_generator']->generate('list_home'));
	    }
	}
	
	public function edit($listId)
	{

		$list  = $this->app['cklist.mapper.list']->find($listId);
		$todos = $this->app['cklist.mapper.todo']->findAllByList($listId);
		$form  = $this->app['form.factory']->create('todo', ['list_id' => $listId]); 
					
		return $this->app['twig']->render('list/edit.html', [ 'list'=>$list, 'form' => $form->createView(), 'todos' => $todos ] );
	}
	
}


