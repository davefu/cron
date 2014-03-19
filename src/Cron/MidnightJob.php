<?php

namespace Cron;

use Nette\Object;
use DateTime;
use DateInterval;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class MidnightJob extends Object implements IJob {

	/** @var IJob */
	protected $job;

	/** @var string */
	protected $name;

	/** @var IRepository */
	protected $repository;

	function __construct($name, IJob $job, IRepository $repository) {
		$this->name = $name;
		$this->job = $job;
		$this->repository = $repository;
	}

	public function run() {
		$record = $this->repository->find($this->name);

		$yesterdaysMidnight = DateTime::createFromFormat('Y-m-d', date('Y-m-d'))->sub(new DateInterval('P1D'));
		if ($record->lastRun !== null) {
			$lastRunMidnight = DateTime::createFromFormat('Y-m-d', $record->lastRun->format('Y-m-d'));
			if ($lastRunMidnight->format('U') > $yesterdaysMidnight->format('U')) {
				return false;
			}
		}

		$this->repository->updateTime($this->name, new DateTime());
		return $this->job->run();
	}

}
