<?php

namespace Foowie\Cron\Job;

use DateTime;
use Foowie\Cron\Repository\IRepository;
use Nette\Object;

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

		$yesterdaysMidnight = new DateTime('midnight - 1 DAY');
		if ($record->lastRun !== null) {
			$lastRunMidnight = new DateTime($record->lastRun->format('Y-m-d'));
			if ($lastRunMidnight > $yesterdaysMidnight) {
				return false;
			}
		}

		$this->repository->updateTime($this->name, new DateTime());
		return $this->job->run();
	}

}
