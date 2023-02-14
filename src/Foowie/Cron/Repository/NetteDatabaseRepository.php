<?php

namespace Foowie\Cron\Repository;

use Foowie\Cron\Exceptions\CronException;
use Foowie\Cron\JobInfo;
use Nette\Database\Explorer;
use Nette\SmartObject;


/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class NetteDatabaseRepository implements IRepository {
	use SmartObject;

	/** @var Explorer */
	protected $database;

	/** @var string */
	protected $tableName;

	function __construct(Explorer $database, $tableName = 'cron') {
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
		if ($record === null) {
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
