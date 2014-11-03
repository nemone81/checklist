<?php

namespace Cklst\Domain;

use Doctrine\DBAL\Connection;

class ListMapper
{
    private $database;
	private $listTable = 'checklists';

    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    /**
     * Returns the number of all todos.
     *
     * @return int
     */
    public function countAll()
    {
        return (int) $this->database->fetchColumn('SELECT COUNT(*) FROM '.$this->listTable);
    }

    public function findAll()
    {
        return $this->database->fetchAll('SELECT * FROM '.$this->listTable);
    }

    public function findAllByUser($user_id)
    {
        return $this->database->fetchAll('SELECT * FROM '.$this->listTable.' WHERE user_id =?', [$user_id] );
    }

    public function findAllWithUserInfo()
    {
        return $this->database->fetchAll('SELECT  '.$this->listTable.'.id, title, user_id, username, name  FROM '.$this->listTable.' JOIN users on '.$this->listTable.'.user_id = users.id' );
    }

    public function find($id)
    {
        return $this->database->fetchAssoc('SELECT * FROM '.$this->listTable.' WHERE id = ?', [ $id ]);
    }

    /**
     * Creates a new todo in the database.
     *
     * @param  string $title
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function create($title, $user_id)
    {
        if (empty($title)) {
            throw new \InvalidArgumentException('Missing title to create a new todo.');
        }

        if (!$this->database->insert($this->listTable, [ 'title' => $title, 'user_id' => $user_id ])) {
            throw new \RuntimeException('Unable to create new todo.');
        }

        return (int) $this->database->lastInsertId();
    }


    public function delete($id)
    {
        return $this->database->delete($this->listTable, [ 'id' => $id ]);
    }
} 