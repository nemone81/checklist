<?php

namespace Cklst\Security\User;

use Gigablah\Silex\OAuth\Security\User\StubUser;

class CkUser extends StubUser
{
	private $id = null;
		
	public function __construct($id= null)
	{
		$this->id = $id;
	}
	
	public function getId()
	{
		return $id;
	}
}