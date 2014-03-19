<?php

namespace Cron;

use Nette\Object;
use Nette\InvalidStateException;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class Repository extends Object implements IRepository {

	/** @var Context */
	protected $connection;

	/** @var string */
	protected $tableName;

	function __construct(/*Context */$connection, $tableName = 'cron') {
		$this->connection = $connection;
		$this->tableName = $tableName;
	}

	public function find($name) {
		$record = $this->connection->table($this->tableName)->where('name', $name)->fetch();
		if ($record === false) {
			if ($this->connection->table($this->tableName)->insert(array('name' => $name)) === false) {
				throw new InvalidStateException();
			}
			$record = $this->connection->table($this->tableName)->where('name', $name)->fetch();
		}
		return $record;
	}

	public function updateTime($name, $time) {
		$this->find($name)->update(array('lastRun' => $time));
	}

}
