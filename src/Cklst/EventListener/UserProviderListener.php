<?php

namespace Cklst\EventListener;

use Gigablah\Silex\OAuth\OAuthEvents;
use Gigablah\Silex\OAuth\Event\GetUserForTokenEvent;
use Gigablah\Silex\OAuth\Security\User\Provider\OAuthUserProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Silex\Application;
/**
 * Listener to match OAuth user with the local user provider.
 *
 * @author Chris Heng <bigblah@gmail.com>
 */
class UserProviderListener implements EventSubscriberInterface
{
	private $app;
	
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
    /**
     * Populate the security token with a user from the local database.
     *
     * @param GetUserForTokenEvent $event
     */
    public function onGetUser(GetUserForTokenEvent $event)
    {
        $userProvider = $event->getUserProvider();
        if (!$userProvider instanceof OAuthUserProviderInterface) {
            return;
        }

        $token = $event->getToken();
		
        if ($user = $userProvider->loadUserByOAuthCredentials($token)) {
			$this->app['session']->set('user_id', $userProvider->getId($user->getEmail()));
			$token->setUser($user);
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            OAuthEvents::USER => 'onGetUser'
        );
    }
}
