<?php

namespace Cklst\Domain;

use Doctrine\DBAL\Connection;

class TodoMapper
{
    private $database;
	private $table = 'todos';

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
        return (int) $this->database->fetchColumn('SELECT COUNT(*) FROM '.$this->table);
    }

    public function findAll()
    {
        return $this->database->fetchAll('SELECT * FROM '.$this->table);
    }

    public function findAllByList($list_id)
    {
        return $this->database->fetchAll('SELECT * FROM '.$this->table.' WHERE list_id =?', [$list_id] );
    }

    public function find($id)
    {
        return $this->database->fetchAssoc('SELECT * FROM '.$this->table.' WHERE id = ?', [ $id ]);
    }

    /**
     * Creates a new todo in the database.
     *
     * @param  string $title
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function create($title, $list_id)
    {
        if (empty($title)) {
            throw new \InvalidArgumentException('Missing title to create a new todo.');
        }

        if (!$this->database->insert($this->table, [ 'title' => $title, 'list_id' => $list_id ])) {
            throw new \RuntimeException('Unable to create new todo.');
        }

        return (int) $this->database->lastInsertId();
    }


    public function delete($id)
    {
        return $this->database->delete($this->table, [ 'id' => $id ]);
    }
	
    public function update($id)
    {
		$count = $this->database->executeUpdate('UPDATE '.$this->table.' SET is_done = !is_done WHERE id = ?', [ $id ] );
		return $count; // 1
        //return $this->database->update($this->table, ['is_done' => 1], [ 'id' => $id ]);
    }	
	
} 