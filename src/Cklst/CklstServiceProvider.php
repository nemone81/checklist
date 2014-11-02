<?php

namespace Cklst;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Cklst\Domain\ListMapper;
use Cklst\Domain\TodoMapper;

class CklstServiceProvider implements ServiceProviderInterface
{
	
    public function register(Application $app)
    {
        $app['cklist.mapper.list'] = $app->share(function () use ($app) {
			return new ListMapper($app['db']);
        });

        $app['cklist.mapper.todo'] = $app->share(function () use ($app) {
			return new TodoMapper($app['db']);
        });
		
    }
	
    public function boot(Application $app)
    {
    }
}


