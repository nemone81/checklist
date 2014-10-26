<?php

namespace Cklst\Security\User\Provider;

use Gigablah\Silex\OAuth\Security\User\Provider\OAuthInMemoryUserProvider;
use Doctrine\DBAL\Connection;
use Gigablah\Silex\OAuth\Security\User\StubUser;
use Symfony\Component\Security\Core\User\UserInterface;

class OAuthDbUserProvider extends OAuthInMemoryUserProvider 
{
    /** @var Connection */
    protected $conn;
		
    /** @var string */
    protected $userTableName = 'users';	
	
    /**
     * Constructor.
     *
     * @param array $users       An array of users
     * @param array $credentials A map of usernames with
     */
    public function __construct(Connection $conn)
    {
		parent::__construct();
		
		$this->conn = $conn;
		
	}
	
    public function createUser(UserInterface $user)
    {
        if (isset($this->users[strtolower($user->getUsername())])) {
            throw new \LogicException('Another user with the same username already exist.');
        }

        $this->users[strtolower($user->getUsername())] = $user;
		
		//save on db
		$this->insert($user);
			
    }
	
    /**
     * Insert a new User instance into the database.
     *
     * @param StubUser $user
     */
    private function insert(StubUser $user)
    {
		// return if user is on db yet
		if($this->checkUser($user->getEmail())) {
			return;
		}
		
        $sql = 'INSERT INTO ' . $this->conn->quoteIdentifier($this->userTableName) 
			  .' (username, name, roles) VALUES (:username, :name, :roles) ';

        $params = array(
            'username' => $user->getEmail(),
            'name'     => $user->getUsername(),
            'roles'    => implode(',', $user->getRoles()),
        );

        $this->conn->executeUpdate($sql, $params);

    }
	
    /**
     * Check if User in on Db
     *
     * @param (string) $username
     */
	private function checkUser($username)
	{

		$rowCount = $this->conn->executeQuery('SELECT id FROM '.$this->conn->quoteIdentifier($this->userTableName).' WHERE username = :username', 
											  array('username' => $username)
											  )->rowCount();
											  
		return $rowCount;
			
	}
	
	
}

