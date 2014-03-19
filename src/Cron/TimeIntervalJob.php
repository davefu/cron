<?php

namespace Cron;

use Nette\Object;
use DateTime;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class TimeIntervalJob extends Object implements IJob {

	/** @var IJob */
	protected $job;

	/** @var string */
	protected $interval;

	/** @var string */
	protected $name;

	/** @var IRepository */
	protected $repository;

	/**
	 * @param $name Unique name of cron job
	 * @param $interval Inteval of cron job in format strtotime @see http://cz1.php.net/manual/en/function.strtotime.php
	 * @param IJob $job Job to call
	 * @param IRepository $repository
	 */
	function __construct($name, $interval, IJob $job, IRepository $repository) {
		$this->name = $name;
		$this->job = $job;
		$this->interval = $interval;
		$this->repository = $repository;
	}

	public function run() {
		$record = $this->repository->find($this->name);
		if ($record->lastRun !== null) {
			$timeLimit = new DateTime($record->lastRun->format('Y-m-d H:i:s') . ' + ' . $this->interval);
			if ($timeLimit > new DateTime()) {
				return false;
			}
		}

		$this->repository->updateTime($this->name, new DateTime());
		return $this->job->run();
	}

}
