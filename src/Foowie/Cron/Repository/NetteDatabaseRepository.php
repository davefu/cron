<?php

namespace Foowie\Cron\Repository;

use Foowie\Cron\Exceptions\CronException;
use Foowie\Cron\JobInfo;
use Nette\Database\Context;
use Nette\Object;


/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class NetteDatabaseRepository extends Object implements IRepository {

	/** @var Context */
	protected $database;

	/** @var string */
	protected $tableName;

	function __construct(Context $database, $tableName = 'cron') {
		$this->database = $database;
		$this->tableName = $tableName;
	}

	/**
	 * @param string $name
	 * @return JobInfo
	 * @throws CronException
	 */
	public function find($name) {
		$record = $this->database->table($this->tableName)->where('name', $name)->fetch();
		if ($record === false) {
			$record = $this->database->table($this->tableName)->insert(array('name' => $name));
			if ($record === false) {
				throw new CronException("Can't insert row into database!");
			}
		}
		return new JobInfo($record->id, $record->name, $record->lastRun, $record->data);
	}

	/**
	 * @param string $name
	 * @param \DateTime $time
	 */
	public function updateTime($name, \DateTime $time) {
		$this->database->table($this->tableName)->where('name', $name)
			->update(array('lastRun' => $time));
	}

}
